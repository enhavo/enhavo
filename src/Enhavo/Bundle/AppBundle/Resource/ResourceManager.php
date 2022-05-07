<?php


namespace Enhavo\Bundle\AppBundle\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\Proxy;
use Enhavo\Bundle\AppBundle\Exception\ResourceException;
use Enhavo\Bundle\AppBundle\Exception\ResourceStopException;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use SM\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceManager
{
    use ContainerAwareTrait;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $em,
        private RegistryInterface $metadataRegistry,
        private array $syliusResources,
        private FactoryInterface $stateMachineFactory
    ) {}

    public function create($resource, $configuration = [])
    {
        $resolver = $this->createOptionResolver([
            'event_name' => ResourceActions::CREATE
        ]);
        $configuration = $resolver->resolve($configuration);
        $this->guessApplicationAndEntityNameIfNotExists($resource, $configuration);

        $this->dispatchPre($resource, $configuration);

        $repository = $this->getRepository($configuration['application_name'], $configuration['entity_name']);
        $repository->add($resource);

        $this->dispatchPost($resource, $configuration);
    }

    public function update($resource, $configuration = [])
    {
        $resolver = $this->createOptionResolver([
            'event_name' => ResourceActions::UPDATE,
            'transition' => null,
            'graph' => null
        ]);
        $configuration = $resolver->resolve($configuration);
        $this->guessApplicationAndEntityNameIfNotExists($resource, $configuration);

        $this->dispatchPre($resource, $configuration);

        if ($configuration['graph'] && $configuration['transition']) {
            $this->stateMachineFactory->get($resource, $configuration['graph'])->apply($configuration['transition']);
        }

        $this->em->flush();

        $this->dispatchPost($resource, $configuration);
    }

    public function delete($resource, $configuration = [])
    {
        $resolver = $this->createOptionResolver([
            'event_name' => ResourceActions::DELETE
        ]);
        $configuration = $resolver->resolve($configuration);
        $this->guessApplicationAndEntityNameIfNotExists($resource, $configuration);

        $this->dispatchPre($resource, $configuration);

        $this->em->remove($resource);
        $this->em->flush();

        $this->dispatchPost($resource, $configuration);
    }

    public function getRepository($applicationName, $entityName): RepositoryInterface
    {
        return $this->container->get(sprintf('%s.repository.%s', $applicationName, $entityName));
    }

    public function getFactory($applicationName, $entityName): FactoryInterface
    {
        return $this->container->get(sprintf('%s.factory.%s', $applicationName, $entityName));
    }

    public function getMetadata($applicationName, $entityName): MetadataInterface
    {
        return $this->metadataRegistry->get(sprintf('%s.%s', $applicationName, $entityName));
    }

    private function dispatch($resource, $eventName)
    {
        $event = new ResourceControllerEvent($resource);
        $this->eventDispatcher->dispatch($event, $eventName);
        if ($event->isStopped()) {
            $exception = new ResourceStopException('Operation on resource was stopped');
            $exception->setEvent($event);
            $exception->setResponse($event->getResponse());
            throw $exception;
        }
        return $event;
    }

    private function dispatchPre($resource, $configuration)
    {
        $this->dispatch($resource, sprintf('enhavo_app.pre_%s', $configuration['event_name']));
        $this->dispatch($resource, sprintf('%s.%s.pre_%s', $configuration['application_name'], $configuration['entity_name'], $configuration['event_name']));
    }

    private function dispatchPost($resource, $configuration)
    {
        $this->dispatch($resource, sprintf('%s.%s.post_%s', $configuration['application_name'], $configuration['entity_name'], $configuration['event_name']));
        $this->dispatch($resource, sprintf('enhavo_app.post_%s', $configuration['event_name']));
    }

    private function createOptionResolver($defaults): OptionsResolver
    {
        $defaults = array_merge([
            'application_name' => null,
            'entity_name' => null,
            'event_name' => null
        ], $defaults);

        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults($defaults);
        return $optionResolver;
    }

    private function guessApplicationAndEntityNameIfNotExists($resource, &$configuration): void
    {
        if ($configuration['application_name'] && $configuration['entity_name']) {
            return;
        }

        $className = get_class($resource);
        if ($resource instanceof Proxy) {
            $className = get_parent_class($resource);
        }

        $typeName = null;
        foreach($this->syliusResources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                if ($typeName !== null) {
                    throw new ResourceException(sprintf('Unable to guess application and entity name for class "%s". It was configured at least twice in "%s", and "%s". Please add configuration application_name and entity_name manually', $className, $typeName, $type));
                }
                $typeName = $type;
            }
        }

        if ($typeName === null) {
            throw new ResourceException(sprintf('Unable to guess application and entity name for class "%s". The class name was never configured as resource'));
        }

        $typeNameParts = explode('.', $typeName);

        $configuration['application_name'] = $typeNameParts[0];
        $configuration['entity_name'] = $typeNameParts[1];
    }
}
