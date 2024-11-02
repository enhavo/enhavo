<?php

namespace Enhavo\Bundle\RevisionBundle\Restore\Metadata;

use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class RestoreProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
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
