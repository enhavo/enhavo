<?php

namespace Enhavo\Bundle\ResourceBundle\Event;

class ResourcePreTransitionEvent extends ResourceEvent
{
    public function __construct(
        object $resource,
        private readonly string $transition,
        private readonly string $graph,
    )
    {
        parent::__construct($resource);
    }

    public function getTransition(): string
    {
        return $this->transition;
    }

    public function getGraph(): string
    {
        return $this->graph;
    }
}
