<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Metadata;

use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Component\Metadata\DriverInterface;

class DuplicateAttributeDriver implements DriverInterface
{
    public function loadClass($className): array|null|false
    {
        $reflection = new \ReflectionClass($className);

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Duplicate::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $options = $arguments[1] ?? [];
                $options['type'] = $arguments[0];

                $properties[$property->getName()] = $options;

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
