<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 18:32
 */

namespace Enhavo\Bundle\SearchBundle\Metadata\Provider;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\PropertyNode;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class PropertyNodeProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if (array_key_exists('properties', $normalizedData) && is_array($normalizedData['properties'])) {
            foreach($normalizedData['properties'] as $property => $config) {
                $propertyNode = new PropertyNode();

                $propertyNode->setType($config['type']);
                unset($config['type']);

                $propertyNode->setOptions($config);
                $propertyNode->setProperty($property);

                $metadata->addProperty($propertyNode);
            }
        }
    }
}
