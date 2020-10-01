<?php


namespace Enhavo\Bundle\AppBundle\Resource;


use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceManager
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * DeleteType constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $em
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
    }

    public function create($resource)
    {
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', ResourceActions::CREATE), new ResourceControllerEvent($resource));
        $this->em->persist($resource);
        $this->em->flush();
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', ResourceActions::CREATE), new ResourceControllerEvent($resource));
    }

    public function update($resource)
    {
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', ResourceActions::UPDATE), new ResourceControllerEvent($resource));
        $this->em->flush();
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', ResourceActions::UPDATE), new ResourceControllerEvent($resource));
    }

    public function delete($resource)
    {
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));
        $this->em->remove($resource);
        $this->em->flush();
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));

    }
}
