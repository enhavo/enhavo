<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Metadata;

use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class StructuredDataProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
            return;
        }

        $this->updateClasses($metadata, $normalizedData);
        $this->updateProperties($metadata, $normalizedData);
        $this->updateMethods($metadata, $normalizedData);
    }

    private function updateClasses(Metadata $metadata, $normalizedData)
    {
        $classes = $metadata->getClasses();

        $normalizedClasses = $normalizedData['class'] ?? [];
        foreach ($normalizedClasses as $key => $config) {
            if (!isset($config['type'])) {
                continue;
            }

            $classes[$config['type']] = $config;
        }

        $metadata->setClasses($classes);
    }

    private function updateProperties(Metadata $metadata, $normalizedData)
    {
        $properties = $metadata->getProperties();

        $normalizedProperties = $normalizedData['properties'] ?? [];
        foreach ($normalizedProperties as $propertyName => $configArray) {
            foreach ($configArray as $config) {
                if (!isset($config['type'])) {
                    continue;
                }

                if (!array_key_exists($propertyName, $properties)) {
                    $properties[$propertyName] = [];
                }

                $properties[$propertyName][$config['type']] = $config;
            }
        }

        $metadata->setProperties($properties);
    }

    private function updateMethods(Metadata $metadata, $normalizedData)
    {
        $methods = $metadata->getMethods();

        $normalizedProperties = $normalizedData['methods'] ?? [];
        foreach ($normalizedProperties as $methodName => $configArray) {
            foreach ($configArray as $config) {
                if (!isset($config['type'])) {
                    continue;
                }

                if (!array_key_exists($methodName, $methods)) {
                    $methods[$methodName] = [];
                }

                $methods[$methodName][$config['type']] = $config;
            }
        }

        $metadata->setMethods($methods);
    }
}
