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

class RegistryMock implements RegistryInterface
{
    private $types = [];

    public function addType($name, $type): self
    {
        return $this->types[$name] = $type;
    }

    public function getType(string $name)
    {
        if (isset($this->types)) {
            return $this->types;
        }

        throw TypeNotFoundException::notFound($name, 'Test', array_keys($this->types));
    }
}
