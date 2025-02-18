<?php
/**
 * MetadataException.php
 *
 * @author gseidel
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
