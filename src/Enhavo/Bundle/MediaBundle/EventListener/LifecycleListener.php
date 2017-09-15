<?php

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\Container;

class LifecycleListener
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function collectGarbage()
    {
        $mediaManager = $this->container->get('enhavo_media.media.media_manager');
        $mediaManager->collectGarbage();
    }
}
