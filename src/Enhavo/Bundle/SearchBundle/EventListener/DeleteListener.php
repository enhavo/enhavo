<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

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
        if($metadata){
            $engine = $this->container->getParameter('enhavo_search.search.index_engine');
            $indexEngine = $this->container->get($engine);
            $indexEngine->unindex($event->getSubject());
        }
    }
}
