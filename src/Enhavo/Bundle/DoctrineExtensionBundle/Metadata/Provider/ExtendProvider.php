<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider;

use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetata;
use Enhavo\Component\Metadata\ProviderInterface;

class ExtendProvider implements ProviderInterface
{
    public function provide(BaseMetata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if (array_key_exists('extends', $normalizedData) || array_key_exists('discrName', $normalizedData)) {
            if (!array_key_exists('extends', $normalizedData)) {
                throw ProviderException::definitionMissing($metadata, 'extends');
            } elseif (!array_key_exists('discrName', $normalizedData)) {
                throw ProviderException::definitionMissing($metadata, 'discrName');
            }

            $metadata->setDiscrName($normalizedData['discrName']);
            $metadata->setExtends($normalizedData['extends']);
        }
    }
}
