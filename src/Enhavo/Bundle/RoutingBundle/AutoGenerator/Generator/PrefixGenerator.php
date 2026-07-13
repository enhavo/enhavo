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

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\RoutingBundle\Util\UniquePrefixGenerator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrefixGenerator extends AbstractGenerator
{
    public function __construct(
        protected UniquePrefixGenerator $uniquePrefixGenerator,
    ) {
    }

    public function generate($resource, $options = [])
    {
        $properties = $this->getSlugifiedProperties($resource, $options);
        if (count($properties)) {
            $route = $this->getProperty($resource, $options['route_property']);
            if (!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix($this->createPrefix($properties, $resource, $options));
        }
    }

    private function createPrefix(array $properties, $resource, array $options): string
    {
        return $this->createUniquePrefix($properties, $resource, $options);
    }

    protected function createUniquePrefix(array $properties, $resource, array $options): string
    {
        return $this->uniquePrefixGenerator->generate($properties, [
            'format' => $options['format'],
            'max_length' => $options['max_length'],
        ]);
    }

    private function getSlugifiedProperties($resource, $options)
    {
        $result = [];

        $properties = $options['properties'];
        if (!is_array($properties)) {
            $properties = [$properties];
        }

        foreach ($properties as $property) {
            $part = $this->getProperty($resource, $property);

            if ($part instanceof \DateTimeInterface) {
                $part = $part->format($options['date_format']);
            }

            $part = strip_tags($part);

            if ($part) {
                $result[$property] = $part;
            }
        }

        return $result;
    }

    private function format(array $properties, array $options)
    {
        if ($options['format']) {
            $string = $options['format'];
            foreach ($properties as $key => $value) {
                $string = str_replace(sprintf('{%s}', $key), $value, $string);
            }

            return $string;
        }

        return sprintf('/%s', join('-', $properties));
    }

    private function cut(string $prefix, int $maxLength): string
    {
        return substr($prefix, 0, max(0, $maxLength));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'format' => null,
            'date_format' => 'Y-m-d',
            'max_length' => 255,
        ]);
        $resolver->setRequired([
            'properties',
        ]);
    }

    public function getType()
    {
        return 'prefix';
    }
}
