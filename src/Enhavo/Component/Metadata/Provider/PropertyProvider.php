<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @throws ProviderException
     */
    public function provide(Metadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof PropertyInterface) {
            throw ProviderException::invalidType($metadata, PropertyInterface::class);
        }

        if (array_key_exists('properties', $normalizedData) && is_array($normalizedData['properties'])) {
            foreach ($normalizedData['properties'] as $name => $propertyData) {
                $metadata->addProperty(new Property($name, $propertyData));
            }
        }
    }
}
