<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\AppBundle\Exception\TypeOptionException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType implements ContainerAwareInterface, TypeInterface
{
    use ContainerAwareTrait;

    /**
     * Return the value the given property and object.
     *
     * @throws PropertyNotExistsException
     */
    protected function getProperty($resource, $property)
    {
        if ('_self' == $property) {
            return $resource;
        }

        $propertyPath = explode('.', $property);

        if (count($propertyPath) > 1) {
            $property = array_shift($propertyPath);
            $newResource = $this->getProperty($resource, $property);
            if (null !== $newResource) {
                $propertyPath = implode('.', $propertyPath);

                return $this->getProperty($newResource, $propertyPath);
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

    protected function parseValue($value, $resource = null)
    {
        if (preg_match('/\$(.+)/', $value, $matches)) {
            $key = $matches[1];
            $request = $this->container->get('request_stack')->getCurrentRequest();
            if ($request->attributes->has($key)) {
                return $request->attributes->get($key);
            }
            if (null !== $resource) {
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
        if (!array_key_exists($key, $options)) {
            $options[$key] = $default;
        }
    }

    protected function setDefaultOption($key, &$options, $default = null)
    {
        if (!array_key_exists($key, $options)) {
            $options[$key] = $default;
        }
    }

    protected function getOption($key, $options, $default = null)
    {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }

    protected function getRequiredOption($key, $options)
    {
        if (array_key_exists($key, $options)) {
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
