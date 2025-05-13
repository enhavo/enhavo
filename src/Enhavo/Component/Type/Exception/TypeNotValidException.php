<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type\Exception;

use Enhavo\Component\Type\TypeExtensionInterface;
use Enhavo\Component\Type\TypeInterface;

class TypeNotValidException extends \InvalidArgumentException
{
    public static function invalidInterface($class)
    {
        return new self(sprintf('"%s" does not implement "%s"', $class, TypeInterface::class));
    }

    public static function invalidExtensionInterface($class)
    {
        return new self(sprintf('"%s" does not implement "%s"', $class, TypeExtensionInterface::class));
    }

    public static function extendedTypeNotExists(string $extensionClass, array $extendedTypes)
    {
        return new self(sprintf('Type "%s" does not exist for extension "%s"', join(',', $extendedTypes), $extensionClass));
    }

    public static function nameExists($name, $namespace, $class)
    {
        return new self(sprintf(
            'The name "%s" already exists for %s types. The name was defined before in class "%s"',
            $name,
            $namespace,
            $class
        ));
    }

    public static function classExists($class, $namespace)
    {
        return new self(sprintf(
            'The class "%s" already registered for %s type before', $class, $namespace
        ));
    }

    public static function circleReferences($classes)
    {
        return new self(sprintf(
            'Circle references detected: "%s" reference back to "%s"', join('" -> "', $classes), $classes[0])
        );
    }

    public static function parentInvalidInterface($class, $parentClass)
    {
        return new self(sprintf(
            'The parent class "%s" referenced by "%s" does not implement interface "%s".', $parentClass, $class, TypeInterface::class
        ));
    }

    public static function parentNotFound($class, $parentClass)
    {
        return new self(sprintf(
            'The parent class "%s" referenced by "%s" does not exist', $parentClass, $class
        ));
    }
}
