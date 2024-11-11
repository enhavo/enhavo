<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 21:53
 */

namespace Enhavo\Component\Metadata\Exception;


class InvalidMetadataException extends \InvalidArgumentException
{
    public static function invalidType($input)
    {
        return new self(sprintf('Parameter class should be a type of string or an object. "%s" given', gettype($input)));
    }

    public static function classNotExists($className)
    {
        return new self(sprintf('The class "%s" does not exists', gettype($className)));
    }
}
