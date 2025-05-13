<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Exception;

class MetadataException extends \Exception
{
    public static function notExists(string|object $class)
    {
        return new self(sprintf('Metadata for class "%s" does\'t exist. Maybe you need to add a new resource or update a model configuration.
        ', is_object($class) ? get_class($class) : $class)
        );
    }
}
