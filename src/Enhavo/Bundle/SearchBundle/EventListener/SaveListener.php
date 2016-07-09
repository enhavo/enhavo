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
    protected $util;
    protected $metadataFactory;

    public function __construct(Container $container, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
        $this->metadataFactory = $metadataFactory;
    }

    public function onSave($event)
    {
        $metadata = $this->metadataFactory->create($event->getSubject());

        //ceck if the current ressource has to get indexed
        if($metadata) {

            //get the right IndexEngine
            $engine = $this->container->getParameter('enhavo_search.search.index_engine');
            $indexEngine = $this->container->get($engine);

            //do indexing
            $indexEngine->index($event->getSubject());
        }
    }
}