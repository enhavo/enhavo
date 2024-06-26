<?php

namespace Enhavo\Bundle\NavigationBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\NavigationBundle\Entity\Content;

class NavigationContentNormalizer extends AbstractDataNormalizer
{
    public function buildData(Data $data, $object, string $format = null, array $context = []): void
    {
        if (!$this->hasSerializationGroup('endpoint.navigation', $context)) {
            return;
        }

        /** @var Content $object */
        $content = $object->getContent();
        if ($content !== null) {
            $normalizedContent = $this->normalizer->normalize($content, null, ['groups' => 'endpoint.navigation']);
            $data->add($normalizedContent);
        }
    }

    public static function getSupportedTypes(): array
    {
        return [Content::class];
    }
}
