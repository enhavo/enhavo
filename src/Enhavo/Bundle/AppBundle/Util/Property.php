<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;

/**
 * @deprecated Use PropertyAccessor
 */
class Property
{
    /**
     * Return the value the given property and object.
     *
     * @throws PropertyNotExistsException
     */
    public static function getProperty($resource, $property)
    {
        if ('_self' == $property) {
            return $resource;
        }

        $propertyPath = explode('.', $property);

        if (count($propertyPath) > 1) {
            $property = array_shift($propertyPath);
            $newResource = self::getProperty($resource, $property);
            if (null !== $newResource) {
                $propertyPath = implode('.', $propertyPath);

                return self::getProperty($newResource, $propertyPath);
            }

            return null;
        }
        $method = sprintf('is%s', ucfirst($property));
        if (method_exists($resource, $method)) {
            return call_user_func([$resource, $method]);
        }

        $method = sprintf('get%s', ucfirst($property));
        if (method_exists($resource, $method)) {
            return call_user_func([$resource, $method]);
        }

        throw new PropertyNotExistsException(sprintf('Trying to call "get%s" or "is%s" on class "%s", but method does not exist. Maybe you spelled it wrong or you didn\'t add the getter for property "%s"', ucfirst($property), ucfirst($property), get_class($resource), $property));
    }
}
