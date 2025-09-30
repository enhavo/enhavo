<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Metadata;

use Enhavo\Bundle\ContentBundle\Attribute\StructuredData;
use Enhavo\Component\Metadata\DriverInterface;

class StructuredDataAttributeDriver implements DriverInterface
{
    public function loadClass($className): array|false|null
    {
        $reflection = new \ReflectionClass($className);


        $class = [];
        $attributes = $reflection->getAttributes(StructuredData::class);
        foreach ($attributes as $attribute) {
            $arguments = $attribute->getArguments();
            $options = $arguments[1] ?? [];
            $options['type'] = $arguments[0];
            $class[] = $options;
        }

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(StructuredData::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $options = $arguments[1] ?? [];
                $options['type'] = $arguments[0];

                if (!array_key_exists($property->getName(), $properties)) {
                    $properties[$property->getName()] = [];
                }

                $properties[$property->getName()][] = $options;
            }
        }

        $methods = [];
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(StructuredData::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $options = $arguments[1] ?? [];
                $options['type'] = $arguments[0];

                if (!array_key_exists($method->getName(), $methods)) {
                    $methods[$method->getName()] = [];
                }

                $methods[$method->getName()][] = $options;
            }
        }

        return [
            'class' => $class,
            'properties' => $properties,
            'methods' => $methods,
        ];
    }

    public function getAllClasses(): array
    {
        return [];
    }
}
