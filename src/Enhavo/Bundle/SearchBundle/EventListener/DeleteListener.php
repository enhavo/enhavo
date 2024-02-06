<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DeleteListener
{
    use ContainerAwareTrait;

    public function onDelete(ResourceEvent $event)
    {
        if($this->container->getParameter('enhavo_search.search.indexing')) {
            //get the right index engine
            $engine = $this->container->getParameter('enhavo_search.search.engine');
            /** @var SearchEngineInterface $indexEngine */
            $indexEngine = $this->container->get($engine);
            $indexEngine->removeIndex($event->getSubject());
        }
    }
}
