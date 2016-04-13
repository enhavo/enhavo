<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Symfony\Component\Yaml\Parser;


class DeleteListener
{
    public function onDelete($event)
    {
        $test = $event;
    }
}