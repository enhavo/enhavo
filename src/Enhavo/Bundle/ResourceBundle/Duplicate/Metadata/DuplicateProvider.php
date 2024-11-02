<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Metadata;

use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class DuplicateProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof \Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata) {
            return;
        }

        $properties = $metadata->getProperties();

        $normalizedProperties = $normalizedData['properties'] ?? [];
        foreach ($normalizedProperties as $key => $value) {
            $properties[$key] = $value;
        }

        $metadata->setProperties($properties);
    }
}
