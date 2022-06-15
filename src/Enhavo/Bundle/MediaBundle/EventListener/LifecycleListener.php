<?php

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Enhavo\Bundle\MediaBundle\Media\MediaManager;

class LifecycleListener
{
    public function __construct(
        protected MediaManager $mediaManager,
        protected bool $enableGarbageCollection,
    ) {}

    public function collectGarbage()
    {
        if ($this->enableGarbageCollection) {
            $this->mediaManager->collectGarbage();
        }
    }
}
