<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Factory;

use Enhavo\Bundle\ApiBundle\Profiler\EndpointDataCollector;
use Enhavo\Component\Type\Factory;
use Enhavo\Component\Type\RegistryInterface;

class EndpointFactory extends Factory
{
    public function __construct(
        readonly string $class,
        readonly RegistryInterface $registry,
        private readonly EndpointDataCollector $dataCollector,
    ) {
        parent::__construct($class, $registry);
    }

    protected function instantiate($class, $arguments)
    {
        return new $class($arguments['type'], $arguments['parents'], $arguments['options'], $arguments['key'], $arguments['extensions'], $this->dataCollector);
    }
}
