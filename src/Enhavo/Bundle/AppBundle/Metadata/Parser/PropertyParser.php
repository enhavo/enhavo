<?php
/**
 * PropertyParser.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Metadata\Parser;

use Enhavo\Bundle\AppBundle\Metadata\ParserInterface;

class PropertyParser implements ParserInterface
{
    public function parse(array &$metadataArray, $configuration)
    {
        if(isset($configuration['properties']) && is_array($configuration['properties'])) {
            $properties = $configuration['properties'];
            foreach($properties as $name => $propertyData) {
                $metadataArray['properties'][$name] = $propertyData;
            }
        }
    }
}