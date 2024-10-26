<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;

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
        }

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
}
