<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 21:48
 */

namespace Enhavo\Component\Metadata\Extension;


interface PropertyInterface
{
    public function addProperty(Property $property);

    public function getProperties(): array;

    public function hasProperty(string $name): bool;

    public function getProperty(string $name): ?Property;
}
