<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    public function onDelete(GenericEvent $event)
    {
        $test = $event;
    }
}