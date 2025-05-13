<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\NavigationBundle\Entity\Content;

class NavigationContentNormalizer extends AbstractDataNormalizer
{
    public function buildData(Data $data, $object, ?string $format = null, array $context = []): void
    {
        if (!$this->hasSerializationGroup('endpoint.navigation', $context)) {
            return;
        }

        /** @var Content $object */
        $content = $object->getContent();
        if (null !== $content) {
            $normalizedContent = $this->normalizer->normalize($content, null, ['groups' => 'endpoint.navigation']);
            $data->add($normalizedContent);
        }
    }

    public static function getSupportedTypes(): array
    {
        return [Content::class];
    }
}
