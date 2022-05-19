<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/*
 * Tells the IndexEngine to index a resource
 */
class SaveListener
{
    use ContainerAwareTrait;

    public function onSave(ResourceEvent $event)
    {
        if($this->container->getParameter('enhavo_search.search.indexing')) {
            //get the right index engine
            $engine = $this->container->getParameter('enhavo_search.search.engine');
            $indexEngine = $this->container->get($engine);
            $indexEngine->index($event->getSubject());
        }
    }
}
