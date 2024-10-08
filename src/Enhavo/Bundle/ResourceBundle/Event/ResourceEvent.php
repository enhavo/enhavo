<?php

namespace Enhavo\Bundle\ResourceBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ResourceEvent extends Event
{
    public function __construct(
        private object $resource,
    )
    {
    }

    public function getResource(): object
    {
        return $this->resource;
    }
}
