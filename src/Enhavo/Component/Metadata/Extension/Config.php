<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Extension;

class Config
{
    public function __construct(
        private string $key,
        private array $config,
    ) {
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
