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

    public function addType($name, $type)
    {
        return $this->types[$name] = $type;
    }

    public function getType(string $name)
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }

        foreach ($this->types as $type) {
            if(get_class($type) === $name) {
                return $type;
            }
        }

        throw TypeNotFoundException::notFound($name, 'Test', array_keys($this->types));
    }
}
