<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\Grid\GridMergeInterface;

class GridConfigurationMerger
{
    public function performMerge(array $configs): array
    {
        $grids = [];

        foreach ($configs as $config) {
            if (isset($config['grids'])) {
                foreach ($config['grids'] as $name => $gridConfig) {
                    if (!isset($grids[$name])) {
                        $grids[$name] = [];
                    }
                    $grids[$name][] = $gridConfig;
                }

                unset($config['grids']);
            }
        }

        $newGridConfig = [];
        foreach ($grids as $name => $gridConfigs) {
            $newGridConfig[$name] = $this->mergeGridConfigs($gridConfigs);
        }

        $configs[] = [
            'grids' => $newGridConfig,
        ];

        return $configs;
    }

    private function mergeGridConfigs(array $gridConfigs): array
    {
        usort($gridConfigs, function ($a, $b) {
            $aPriority = $a['priority'] ?? 0;
            $bPriority = $b['priority'] ?? 0;

            return $aPriority - $bPriority;
        });

        $gridConfig = null;

        foreach ($gridConfigs as $config) {
            if ($gridConfig === null) {
                $gridConfig = $config;
                continue;
            }

            if ($this->isCallable($config)) {
                $gridConfig = $config['class']::mergeOptions($gridConfig, $config);
            } else {
                $gridConfig = $config;
            }
        }

        if (array_key_exists('priority', $gridConfig)) {
            unset($gridConfig['priority']);
        }

        if (array_key_exists('overwrite', $gridConfig)) {
            unset($gridConfig['overwrite']);
        }

        return $gridConfig;
    }

    private function isCallable($config): bool
    {
        if (isset($config['overwrite']) && $config['overwrite'] === true) {
            return false;
        }

        if (!isset($config['class'])) {
            return false;
        }

        if (is_subclass_of($config['class'], GridMergeInterface::class)) {
            return true;
        }

        return false;
    }
}
