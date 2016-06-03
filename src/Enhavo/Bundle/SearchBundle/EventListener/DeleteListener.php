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
        $engine = $this->container->getParameter('enhavo_search.search.index_engine');
        $indexEngine = $this->container->get($engine);
        $indexEngine->unindex($event->getSubject());
    }
}
