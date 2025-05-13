<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Metadata;

use Enhavo\Component\Metadata\Metadata as BaseMetadata;
use Enhavo\Component\Metadata\ProviderInterface;

class DuplicateProvider implements ProviderInterface
{
    public function provide(BaseMetadata $metadata, $normalizedData)
    {
        if (!$metadata instanceof Metadata) {
            return;
        }

        $properties = $metadata->getProperties();

        $normalizedProperties = $normalizedData['properties'] ?? [];
        foreach ($normalizedProperties as $propertyName => $config) {
            $configArray = is_int(array_keys($config)[0]) ? $config : [$config];
            foreach ($configArray as $value) {
                if (!array_key_exists($propertyName, $properties)) {
                    $properties[$propertyName] = [];
                }

                if (!$this->configExists($properties[$propertyName], $value)) {
                    $properties[$propertyName][] = $value;
                }
            }
        }

        $metadata->setProperties($properties);
    }

    private function configExists($configs, $config): bool
    {
        foreach ($configs as $checkConfig) {
            if ($config === $checkConfig) {
                return true;
            }
        }

        return false;
    }
}
