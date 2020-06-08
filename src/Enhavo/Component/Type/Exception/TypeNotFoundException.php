<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 14:07
 */

namespace Enhavo\Component\Type\Exception;

class TypeNotFoundException extends \InvalidArgumentException
{
    public static function notFound($name, $namespace, $possibilities)
    {
        return new self(sprintf(
            '%s type "%s" not found. Did you mean one of them "%s".',
            $namespace,
            $name,
            implode(', ', $possibilities)
        ));
    }
}
