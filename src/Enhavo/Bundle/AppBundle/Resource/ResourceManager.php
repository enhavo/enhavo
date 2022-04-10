<?php


namespace Enhavo\Bundle\AppBundle\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceManager
{
    use ContainerAwareTrait;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $em,
        private RegistryInterface $metadataRegistry,
    ) {}

    public function create($resource)
    {
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.pre_%s', ResourceActions::CREATE));
        $this->em->persist($resource);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.post_%s', ResourceActions::CREATE));
    }

    public function update($resource)
    {
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.pre_%s', ResourceActions::UPDATE));
        $this->em->flush();
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.post_%s', ResourceActions::UPDATE));
    }

    public function delete($resource)
    {
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.pre_%s', ResourceActions::DELETE));
        $this->em->remove($resource);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new ResourceControllerEvent($resource), sprintf('enhavo_app.post_%s', ResourceActions::DELETE));
    }

    public function getRepository($applicationName, $entityName): RepositoryInterface
    {
        return $this->container->get(sprintf('%s.repository.%s', $applicationName, $entityName));
    }

    public function getMetadata($applicationName, $entityName): MetadataInterface
    {
        return $this->metadataRegistry->get(sprintf('%s.%s', $applicationName, $entityName));
    }
}
