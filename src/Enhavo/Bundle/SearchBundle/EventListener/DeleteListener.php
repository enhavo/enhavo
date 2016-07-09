<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

/*
 * tells the IndexEngine to unindex a resource
 */
class DeleteListener
{
    protected $container;
    protected $metadataFactory;

    public function __construct(Container $container, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
        $this->metadataFactory = $metadataFactory;
    }

    public function onDelete(GenericEvent $event)
    {
        $metadata = $this->metadataFactory->create($event->getSubject());

        //check if the current subject was indexed
        if($metadata){

            //get the right IndexEngine
            $engine = $this->container->getParameter('enhavo_search.search.index_engine');
            $indexEngine = $this->container->get($engine);

            //do the unindexing
            $indexEngine->unindex($event->getSubject());
        }
    }
}
