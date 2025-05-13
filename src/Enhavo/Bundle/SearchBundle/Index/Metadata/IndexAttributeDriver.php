<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;

use Enhavo\Bundle\SearchBundle\Attribute\Index;
use Enhavo\Component\Metadata\DriverInterface;

class IndexAttributeDriver implements DriverInterface
{
    public function loadClass($className): array|false|null
    {
        $reflection = new \ReflectionClass($className);
        $properties = [];
        $attributes = $reflection->getAttributes(Index::class);
        foreach ($attributes as $attribute) {
            $arguments = $attribute->getArguments();
            $options = $arguments[1] ?? [];
            $options['type'] = $arguments[0];
            $name = $options['name'];
            unset($options['name']);
            $properties[$name] = $options;
        }
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Index::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $options = $arguments[1] ?? [];
                $options['type'] = $arguments[0];
                if (!array_key_exists('property', $options)) {
                    $options['property'] = $property->getName();
                }

                $properties[$property->getName()] = $options;
            }
        }

        return [
            'index' => $properties,
        ];
    }

    public function getAllClasses(): array
    {
        return [];
    }
}
