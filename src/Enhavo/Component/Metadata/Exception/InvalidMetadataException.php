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

class InvalidMetadataException extends \InvalidArgumentException
{
    public static function invalidType($input)
    {
        return new self(sprintf('Parameter class should be a type of string or an object. "%s" given', gettype($input)));
    }

    public static function classNotExists($className)
    {
        return new self(sprintf('The class "%s" does not exist', gettype($className)));
    }
}
