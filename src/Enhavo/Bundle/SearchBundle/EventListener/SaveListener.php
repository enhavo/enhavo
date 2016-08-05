<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

/*
 * Tells the IndexEngine to index a resource
 */
class SaveListener
{
    protected $container;

    public function __construct(Container $container, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
    }

    public function onSave($event)
    {
        //get the right IndexEngine
        $engine = $this->container->getParameter('enhavo_search.search.index_engine');
        $indexEngine = $this->container->get($engine);
        $indexEngine->index($event->getSubject());
    }
}