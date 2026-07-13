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
        private UniquePrefixGenerator $uniquePrefixGenerator,
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

    protected function getExistsCallback($resource, array $options): ?callable
    {
        return null;
    }

    private function createPrefix(array $properties, $resource, array $options): string
    {
        if (!$options['unique']) {
            return $this->cut($this->format($properties, $options), $options['max_length']);
        }

        $exists = $this->getExistsCallback($resource, $options);

        if ($options['unique_property']) {
            return $this->createUniquePropertyPrefix($properties, $options, $exists);
        }

        return $this->uniquePrefixGenerator->generate($properties, $resource, [
            'format' => $options['format'],
            'max_length' => $options['max_length'],
            'exists' => $exists,
        ]);
    }

    private function createUniquePropertyPrefix(array $properties, array $options, ?callable $exists): string
    {
        $this->checkUniqueProperty($properties, $options);

        $isFirstTry = true;
        while ($this->uniquePrefixGenerator->exists($this->cut($this->format($properties, $options), $options['max_length']), ['exists' => $exists])) {
            $properties = $this->increaseProperties($properties, $options, $isFirstTry);
            $isFirstTry = false;
        }

        return $this->cut($this->format($properties, $options), $options['max_length']);
    }

    private function getSlugifiedProperties($resource, $options)
    {
        $result = [];

        $properties = $options['properties'];
        if (!is_array($properties)) {
            $properties = [$properties];
        }

        foreach ($properties as $property) {
            $slug = $this->getSlug($this->getProperty($resource, $property), $options);
            if ($slug) {
                $result[$property] = $slug;
            }
        }

        return $result;
    }

    private function getSlug($input, $options)
    {
        if ($input instanceof \DateTimeInterface) {
            $input = $input->format($options['date_format']);
        }

        return Slugifier::slugify(strip_tags($input));
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

    private function checkUniqueProperty($properties, $options)
    {
        if (!isset($properties[$options['unique_property']])) {
            throw new \InvalidArgumentException(sprintf('The unique_property option "%s" don\'t exists in option properties. Available properties are "%s"', $options['unique_property'], is_array($options['properties']) ? join(',', $options['properties']) : $options['properties']));
        }
    }

    private function increaseProperties($properties, $options, $isFirstTry)
    {
        $uniqueProperty = $this->getUniqueProperty($properties, $options);
        $string = $properties[$uniqueProperty];

        $properties[$this->getUniqueProperty($properties, $options)] = $this->increaseString($string, $isFirstTry);

        return $properties;
    }

    private function increaseString($string, $isFirstTry)
    {
        if (!$isFirstTry) {
            $isMatch = preg_match('/^(.*)-([0-9]+)$/', $string, $matches);
            if ($isMatch && isset($matches[1]) && isset($matches[2])) {
                $string = sprintf('%s-%u', $matches[1], intval($matches[2]) + 1);

                return $string;
            }
        }

        return sprintf('%s-1', $string);
    }

    private function getUniqueProperty($properties, $options)
    {
        if ($options['unique_property']) {
            return $options['unique_property'];
        }

        return array_key_last($properties);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'format' => null,
            'unique' => true,
            'unique_property' => null,
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
