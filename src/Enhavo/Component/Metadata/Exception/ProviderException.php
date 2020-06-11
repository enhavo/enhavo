<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 21:53
 */

namespace Enhavo\Component\Metadata\Exception;


use Enhavo\Component\Metadata\Metadata;

class ProviderException extends \Exception
{
    public static function invalidInterface(Metadata $metadata, string $interface)
    {
        return new self(sprintf('The Metadata class "%s" should implement interface "%s" to provide data', get_class($metadata), $interface));
    }
}
