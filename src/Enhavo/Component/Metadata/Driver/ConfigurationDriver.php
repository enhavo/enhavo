<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Driver;

use Enhavo\Component\Metadata\DriverInterface;

class ConfigurationDriver implements DriverInterface
{
    public function __construct(
        private readonly array $configuration,
    ) {
    }

    public function loadClass($className): array|false|null
    {
        if (array_key_exists($className, $this->configuration)) {
            return $this->configuration[$className];
        }

        return false;
    }

    public function getAllClasses(): array
    {
        return array_keys($this->configuration);
    }
}
