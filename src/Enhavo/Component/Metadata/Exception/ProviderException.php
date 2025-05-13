<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Exception;

use Enhavo\Component\Metadata\Metadata;

class ProviderException extends \InvalidArgumentException
{
    public static function invalidType(Metadata $metadata, string $type)
    {
        return new self(sprintf('The Metadata class "%s" should be type of "%s" to provide data', get_class($metadata), $type));
    }

    public static function definitionMissing(Metadata $metadata, $property)
    {
        if (is_array($property)) {
            return new self(sprintf('The Class "%s" should define following: "%s", but they are missing', $metadata->getClassName(), join('", "', $property)));
        }

        return new self(sprintf('The Class "%s" should define "%s", but is missing', $metadata->getClassName(), $property));
    }
}
