<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 11/10/16
 * Time: 13:10
 */

namespace Enhavo\Component\Type\Exception;

class TypeCreateException extends \InvalidArgumentException
{
    public static function missingOption($class, $options)
    {
        return new self(sprintf('Option "type" is missing to create "%s". Given options are [%s]', join(',', $options), $class));
    }
}
