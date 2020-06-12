<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 23:58
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Exception;


class ResolveException extends \InvalidArgumentException
{
    public static function invalidEntity($entity)
    {
        return new self(sprintf('Can\'t resolve name for entity with class "%s"', get_class($entity)));
    }
}
