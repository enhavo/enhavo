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

class PropertyAttributeTypeDriver implements DriverInterface
{
    public function __construct(
        private readonly string $attributeClass,
    ) {
    }

    public function loadClass($className): array|false|null
    {
        $reflection = new \ReflectionClass($className);

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes($this->attributeClass);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $options = $arguments[1] ?? [];
                $options['type'] = $arguments[0];

                $properties[$property->getName()] = $options;
            }
        }

        return $properties;
    }

    public function getAllClasses(): array
    {
        return [];
    }
}
