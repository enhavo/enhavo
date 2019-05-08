<?php

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Enhavo\Bundle\MediaBundle\Media\MediaManager;

class LifecycleListener
{
    /**
     * @var MediaManager
     */
    protected $mediaManager;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function collectGarbage()
    {
        $this->mediaManager->collectGarbage();
    }
}
