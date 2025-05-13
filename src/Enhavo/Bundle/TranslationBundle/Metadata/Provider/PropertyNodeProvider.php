<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata\Provider;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class PropertyNodeProvider implements ProviderInterface
{
    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct()
    {
        $this->nameTransformer = new NameTransformer();
    }

    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if (array_key_exists('properties', $normalizedData) && is_array($normalizedData['properties'])) {
            foreach ($normalizedData['properties'] as $property => $config) {
                $propertyNode = new PropertyNode();

                $propertyNode->setType($config['type']);
                unset($config['type']);

                $propertyNode->setOptions($config);
                $propertyNode->setProperty($this->nameTransformer->camelCase($property, true));

                $metadata->addProperty($propertyNode);
            }
        }
    }
}
