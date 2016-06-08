<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class DeleteListener
{
    protected $em;
    protected $container;
    protected $util;

    public function __construct(EntityManager $em, Container $container, SearchUtil $util)
    {
        $this->em = $em;
        $this->container = $container;
        $this->util = $util;
    }

    public function onDelete(GenericEvent $event)
    {
        $searchYaml = $this->util->getSearchYaml($event->getSubject());
        if($searchYaml){
            $engine = $this->container->getParameter('enhavo_search.search.index_engine');
            $indexEngine = $this->container->get($engine);
            $indexEngine->unindex($event->getSubject());
        }
    }
}
