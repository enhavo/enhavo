<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class ResourceMerger
{
    public function performMerge(array $configs): array
    {
        $resources = [];

        foreach ($configs as $config) {
            if (isset($config['resources'])) {
                foreach ($config['resources'] as $name => $resourceConfig) {
                    if (!isset($resources[$name])) {
                        $resources[$name] = [];
                    }
                    $resources[$name][] = $resourceConfig;
                }

                unset($config['resources']);
            }
        }

        $newResourceConfig = [];

        foreach ($resources as $name => $resourceConfigs) {
            $newResourceConfig[$name] = $this->mergeConfigs($resourceConfigs);
            $newResourceConfig[$name] = $this->setDefaults($newResourceConfig[$name]);
        }

        $this->validateResources($newResourceConfig);

        $configs[] = [
            'resources' => $newResourceConfig,
        ];

        return $configs;
    }

    private function mergeConfigs($configs): array
    {
        usort($configs, function ($a, $b) {
            $aPriority = $a['priority'] ?? 0;
            $bPriority = $b['priority'] ?? 0;

            return $aPriority - $bPriority;
        });

        $finalConfig = [];
        foreach ($configs as $config) {
            $finalConfig = $this->merge($finalConfig, $config);
        }

        if (isset($finalConfig['priority'])) {
            unset($finalConfig['priority']);
        }

        return $finalConfig;
    }

    private function merge($before, $current)
    {
        foreach ($current as $key => $value) {
            if (array_key_exists($key, $before) && is_array($before[$key])) {
                $before[$key] = array_merge($before[$key], $value);
            } else {
                $before[$key] = $value;
            }
        }

        return $before;
    }

    private function validateResources($resources): void
    {
        $models = [];
        foreach ($resources as $name => $resourceConfig) {
            $model = $resourceConfig['classes']['model'];
            if (array_key_exists($model, $models)) {
                throw new \InvalidArgumentException(sprintf('A model class can have only one resource alias. Trying to apply model "%s on "%s", but alias "%s" already use it.', $model, $name, $models[$model]));
            }
            $models[$model] = $name;
        }
    }

    private function setDefaults($config): array
    {
        if (!isset($config['classes']['repository'])) {
            $config['classes']['repository'] = EntityRepository::class;
        }

        if (!isset($config['classes']['factory'])) {
            $config['classes']['factory'] = Factory::class;
        }

        return $config;
    }
}
