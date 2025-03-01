<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;

/*
 * Tells the IndexEngine to index a resource
 */
class SaveListener
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine
    )
    {
    }

    public function onSave(ResourceEvent $event)
    {
        $this->searchEngine->index($event->getSubject());
    }
}
