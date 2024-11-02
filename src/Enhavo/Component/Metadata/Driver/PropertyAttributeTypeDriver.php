<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata\Driver;

use Enhavo\Component\Metadata\DriverInterface;

class PropertyAttributeTypeDriver implements DriverInterface
{
    public function __construct(
        private readonly string $attributeClass,
    )
    {
    }

    public function loadClass($className): array|null|false
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
