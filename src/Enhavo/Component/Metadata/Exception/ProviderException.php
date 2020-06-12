<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 21:53
 */

namespace Enhavo\Component\Metadata\Exception;


use Enhavo\Component\Metadata\Metadata;

class ProviderException extends \InvalidArgumentException
{
    public static function invalidType(Metadata $metadata, string $type)
    {
        return new self(sprintf('The Metadata class "%s" should be type of "%s" to provide data', get_class($metadata), $type));
    }

    public static function definitionMissing(Metadata $metadata, string $property)
    {
        return new self(sprintf('The Class "%s" should define "%s", but is missing', $metadata->getClassName(), $property));
    }
}
