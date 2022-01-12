<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/*
 * Tells the IndexEngine to index a resource
 */
class SaveListener
{
    use ContainerAwareTrait;

    /**
     * @param ResourceControllerEvent $event
     */
    public function onSave(ResourceControllerEvent $event)
    {
        if($this->container->getParameter('enhavo_search.search.indexing')) {
            //get the right index engine
            $engine = $this->container->getParameter('enhavo_search.search.engine');
            $indexEngine = $this->container->get($engine);
            $indexEngine->index($event->getSubject());
        }
    }
}
