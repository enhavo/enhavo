<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 13:48
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
     * @param $resource
     * @param $property
     * @return mixed
     * @throws PropertyNotExistsException
     */
    public static function getProperty($resource, $property)
    {
        if($property == '_self') {
            return $resource;
        }

        $propertyPath = explode('.', $property);

        if(count($propertyPath) > 1) {
            $property = array_shift($propertyPath);
            $newResource = self::getProperty($resource, $property);
            if($newResource !== null) {
                $propertyPath = implode('.', $propertyPath);
                return self::getProperty($newResource, $propertyPath);
            } else {
                return null;
            }
        } else {
            $method = sprintf('is%s', ucfirst($property));
            if(method_exists($resource, $method)) {
                return call_user_func(array($resource, $method));
            }

            $method = sprintf('get%s', ucfirst($property));
            if(method_exists($resource, $method)) {
                return call_user_func(array($resource, $method));
            }
        }

        throw new PropertyNotExistsException(sprintf(
            'Trying to call "get%s" or "is%s" on class "%s", but method does not exist. Maybe you spelled it wrong or you didn\'t add the getter for property "%s"',
            ucfirst($property),
            ucfirst($property),
            get_class($resource),
            $property
        ));
    }
}
