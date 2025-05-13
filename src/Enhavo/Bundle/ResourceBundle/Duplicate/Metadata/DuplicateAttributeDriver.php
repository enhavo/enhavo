<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Metadata;

use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Component\Metadata\DriverInterface;

class DuplicateAttributeDriver implements DriverInterface
{
    public function loadClass($className): array|false|null
    {
        $reflection = new \ReflectionClass($className);

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Duplicate::class);
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

        return [
            'properties' => $properties,
        ];
    }

    public function getAllClasses(): array
    {
        return [];
    }
}
