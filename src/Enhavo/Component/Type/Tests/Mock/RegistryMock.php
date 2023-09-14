<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 13:10
 */

namespace Enhavo\Component\Type\Tests\Mock;

use Enhavo\Component\Type\Exception\TypeNotFoundException;
use Enhavo\Component\Type\RegistryInterface;
use Enhavo\Component\Type\TypeInterface;

class RegistryMock implements RegistryInterface
{
    private $types = [];
    private $extensions = [];

    public function register($name, $type)
    {
        return $this->types[$name] = $type;
    }

    public function registerExtension($name, $extension)
    {
        return $this->extensions[$name] = $extension;
    }

    public function getType(string $name): TypeInterface
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }

        foreach ($this->types as $type) {
            if (get_class($type) === $name) {
                return $type;
            }
        }

        throw TypeNotFoundException::notFound($name, 'Test', array_keys($this->types));
    }

    public function getExtensions(TypeInterface $type): array
    {
        foreach ($this->types as $name => $foundType) {
            if ($foundType === $type) {
                if (isset($this->extensions[$name])) {
                    return [$this->extensions[$name]];
                }
            }
        }

        return [];
    }
}
