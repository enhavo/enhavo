<?php

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerMock implements ContainerInterface
{
    public $services = [];
    public $parameters = [];
    public $initialized = true;

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

    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }
}
