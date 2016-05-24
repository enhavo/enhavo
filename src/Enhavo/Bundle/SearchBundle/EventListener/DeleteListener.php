<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\DependencyInjection\Container;

class DeleteListener
{
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function onDelete(GenericEvent $event)
    {
        $indexEngine = $this->container->get('enhavo_search_index_engine');
        $indexEngine->unindex($event->getSubject());
    }
}
