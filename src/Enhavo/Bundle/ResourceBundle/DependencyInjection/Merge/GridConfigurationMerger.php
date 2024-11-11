<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;

class GridConfigurationMerger extends AbstractConfigurationMerger
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
        $cachedConfigs = [];
        foreach ($grids as $name => $gridConfigs) {
            try {
                $newGridConfig[$name] = $this->mergeConfigs($gridConfigs, $grids, $name, $cachedConfigs);
            } catch (\Exception $exception) {
                throw new \Exception(sprintf('Error merging grid configs: %s', $exception->getMessage()), 0, $exception);
            }
        }

        $configs[] = [
            'grids' => $newGridConfig,
        ];

        return $configs;
    }
}
