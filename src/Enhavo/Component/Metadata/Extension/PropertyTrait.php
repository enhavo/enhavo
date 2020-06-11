<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 14:09
 */

namespace Enhavo\Component\Metadata\Extension;


trait PropertyTrait
{
    /** @var Property[] */
    private $properties = [];

    public function addProperty(Property $property)
    {
        $this->properties[] = $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function hasProperty(string $name): bool
    {
        return $this->getProperty($name) !== null;
    }

    public function getProperty(string $name): ?Property
    {
        foreach($this->properties as $property) {
            if($property->getName() === $name) {
                return $property;
            }
        }

        return null;
    }
}
