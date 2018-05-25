<?php
namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    use ContainerAwareTrait;

    public function onDelete(GenericEvent $event)
    {
        if($this->container->getParameter('enhavo_search.search.indexing')) {
            //get the right index engine
            $engine = $this->container->getParameter('enhavo_search.search.engine');
            /** @var EngineInterface $indexEngine */
            $indexEngine = $this->container->get($engine);
            $indexEngine->removeIndex($event->getSubject());
        }
    }
}
