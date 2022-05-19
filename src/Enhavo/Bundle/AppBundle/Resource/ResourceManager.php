<?php


namespace Enhavo\Bundle\AppBundle\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Event\ResourcePostCreateEvent;
use Enhavo\Bundle\AppBundle\Event\ResourcePostDeleteEvent;
use Enhavo\Bundle\AppBundle\Event\ResourcePostUpdateEvent;
use Enhavo\Bundle\AppBundle\Event\ResourcePreCreateEvent;
use Enhavo\Bundle\AppBundle\Event\ResourcePreDeleteEvent;
use Enhavo\Bundle\AppBundle\Event\ResourcePreUpdateEvent;
use SM\Factory\FactoryInterface as SMFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceManager
{
    use ContainerAwareTrait;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $em,
        private RegistryInterface $metadataRegistry,
        private SMFactoryInterface $stateMachineFactory
    ) {}

    public function save(ResourceInterface $resource, ?string $transition = null, ?string $graph = null)
    {
        if ($resource->getId() === null) {
            $this->create($resource, $transition, $graph);
        } else {
            $this->update($resource, $transition, $graph);
        }
    }

    public function create(ResourceInterface $resource, ?string $transition = null, ?string $graph = null)
    {
        $this->dispatch(new ResourcePreCreateEvent($resource), 'pre_create');

        if ($transition && $graph) {
            $this->stateMachineFactory->get($resource, $graph)->apply($transition);
        }

        $repository = $this->em->getRepository(get_class($resource));
        if ($repository instanceof RepositoryInterface) {
            $repository->add($resource);
        } else {
            $this->em->persist($resource);
            $this->em->flush();
        }

        $this->dispatch(new ResourcePostCreateEvent($resource), 'post_create');
    }

    public function update(ResourceInterface $resource, ?string $transition = null, ?string $graph = null)
    {
        $this->dispatch(new ResourcePreUpdateEvent($resource), 'pre_update');

        if ($transition && $graph) {
            $this->stateMachineFactory->get($resource, $graph)->apply($transition);
        }
        $this->em->flush();

        $this->dispatch(new ResourcePostUpdateEvent($resource), 'post_update');
    }

    public function canApplyTransition(ResourceInterface $resource, ?string $transition = null, ?string $graph = null): bool
    {
        return $this->stateMachineFactory->get($resource, $graph)->can($transition);
    }

    public function delete(ResourceInterface $resource)
    {
        $this->dispatch(new ResourcePreDeleteEvent($resource), 'pre_delete');

        $this->em->remove($resource);
        $this->em->flush();

        $this->dispatch(new ResourcePostDeleteEvent($resource), 'post_delete');
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

    private function dispatch($event, $eventName)
    {
        $this->eventDispatcher->dispatch($event, sprintf('enhavo_app.%s', $eventName));
    }
}
