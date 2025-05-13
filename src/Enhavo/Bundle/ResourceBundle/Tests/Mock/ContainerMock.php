<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Mock;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerMock implements ContainerInterface
{
    public array $services = [];
    public array $parameters = [];
    public bool $initialized = true;

    public function set(string $id, ?object $service)
    {
        $this->services[$id] = $service;
    }

    public function get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE): ?object
    {
        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function initialized(string $id): bool
    {
        return $this->initialized;
    }

    public function getParameter(string $name)
    {
        return $this->parameters[$name];
    }

    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    public function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }
}
