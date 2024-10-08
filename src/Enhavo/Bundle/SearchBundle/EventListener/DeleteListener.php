<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;

class DeleteListener
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine
    )
    {
    }

    public function onDelete(ResourceEvent $event)
    {

        $this->searchEngine->removeIndex($event->getSubject());

    }
}
