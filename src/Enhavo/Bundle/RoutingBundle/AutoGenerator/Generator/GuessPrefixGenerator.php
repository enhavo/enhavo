<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuessPrefixGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $value = $this->guessProperties($resource, $options);
        if (null !== $value) {
            /** @var RouteInterface $route */
            $route = $this->getProperty($resource, $options['route_property']);
            if (!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix(sprintf('/%s', Slugifier::slugify($value)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'properties' => [
                'title',
                'name',
                'headline',
                'slug',
            ],
            'route_property' => 'route',
            'overwrite' => false,
        ]);
    }

    public function getType()
    {
        return 'guess_prefix';
    }

    private function guessProperties($model, $options)
    {
        $properties = $this->getContextProperties($model, $options);
        foreach ($properties as $property) {
            $value = $this->getProperty($model, $property);
            if (null !== $value) {
                return $value;
            }
        }

        return null;
    }

    private function getContextProperties($model, $options)
    {
        $possibleProperties = [];

        foreach ($options['properties'] as $property) {
            try {
                $this->getProperty($model, $property);
                $possibleProperties[] = $property;
            } catch (PropertyNotExistsException $e) {
                continue;
            }
        }

        return $possibleProperties;
    }
}
