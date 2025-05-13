<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Event;

class ResourcePostTransitionEvent extends ResourceEvent
{
    public function __construct(
        object $resource,
        private readonly string $transition,
        private readonly string $graph,
    ) {
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
