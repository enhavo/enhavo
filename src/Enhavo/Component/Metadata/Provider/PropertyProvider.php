<?php
/**
 * PropertyParser.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Metadata\Parser;

use Enhavo\Component\Metadata\Metadata;

class PropertyProvider
{
    public function provide(Metadata $metadata, $normalizedData)
    {
        if(isset($configuration['properties']) && is_array($configuration['properties'])) {
            $properties = $configuration['properties'];
            foreach($properties as $name => $propertyData) {
                $metadataArray['properties'][$name] = $propertyData;
            }
        }
    }
}
