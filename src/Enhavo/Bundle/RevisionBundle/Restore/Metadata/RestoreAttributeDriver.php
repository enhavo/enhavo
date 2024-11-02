<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\RevisionBundle\Restore\Metadata;

use Enhavo\Bundle\RevisionBundle\Attribute\Restore;
use Enhavo\Component\Metadata\DriverInterface;

class RestoreAttributeDriver implements DriverInterface
{
    public function loadClass($className): array|null|false
    {
        $reflection = new \ReflectionClass($className);

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Restore::class);
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
