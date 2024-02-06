<?php
/**
 * PropertyParser.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata\Provider;

use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Extension\Property;
use Enhavo\Component\Metadata\Extension\PropertyInterface;
use Enhavo\Component\Metadata\Metadata;
use Enhavo\Component\Metadata\ProviderInterface;

class PropertyProvider implements ProviderInterface
{
    /**
     * @param Metadata $metadata
     * @param $normalizedData
     * @throws ProviderException
     */
    public function provide(Metadata $metadata, $normalizedData)
    {
        if(!$metadata instanceof PropertyInterface) {
            throw ProviderException::invalidType($metadata, PropertyInterface::class);
        }

        if(array_key_exists('properties', $normalizedData) && is_array($normalizedData['properties'])) {
            foreach($normalizedData['properties'] as $name => $propertyData) {
                $metadata->addProperty(new Property($name, $propertyData));
            }
        }
    }
}
