<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 13:48
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\AppBundle\Exception\TypeOptionException;

abstract class AbstractType implements ContainerAwareInterface, TypeInterface
{
    use ContainerAwareTrait;

    /**
     * Return the value the given property and object.
     *
     * @param $resource
     * @param $property
     * @return mixed
     * @throws PropertyNotExistsException
     */
    protected function getProperty($resource, $property)
    {
        if($property == '_self') {
            return $resource;
        }

        $propertyPath = explode('.', $property);

        if(count($propertyPath) > 1) {
            $property = array_shift($propertyPath);
            $newResource = $this->getProperty($resource, $property);
            if($newResource !== null) {
                $propertyPath = implode('.', $propertyPath);
                return $this->getProperty($newResource, $propertyPath);
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

    protected function parseValue($value, $resource = null)
    {
        if(preg_match('/\$(.+)/', $value, $matches)) {
            $key = $matches[1];
            $request = $this->container->get('request_stack')->getCurrentRequest();
            if($request->attributes->has($key)) {
                return $request->attributes->get($key);
            }
            if($resource !== null) {
                return $this->getProperty($resource, $key);
            }
        }
        return $value;
    }

    protected function renderTemplate($template, $parameters = [])
    {
        return $this->container->get('twig')->render($template, $parameters);
    }

    protected function setOption($key, &$options, $default = null)
    {
        if(!array_key_exists($key, $options)) {
            $options[$key] = $default;
        }
    }

    protected function setDefaultOption($key, &$options, $default = null)
    {
        if(!array_key_exists($key, $options)) {
            $options[$key] = $default;
        }
    }

    protected function getOption($key, $options, $default = null)
    {
        if(array_key_exists($key, $options)) {
            return $options[$key];
        }
        return $default;
    }

    protected function getRequiredOption($key, $options)
    {
        if(array_key_exists($key, $options)) {
            return $options[$key];
        }
        throw new TypeOptionException(sprintf('option "%s" is required', $key));
    }

    protected function resolveOptions($defaultOptions, $options)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults($defaultOptions);
        return $optionResolver->resolve($options);
    }
}
