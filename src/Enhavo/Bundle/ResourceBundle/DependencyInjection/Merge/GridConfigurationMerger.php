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

use Enhavo\Bundle\ResourceBundle\Grid\Grid;

class GridConfigurationMerger extends AbstractConfigurationMerger
{
    public function __construct(
        protected string $defaultClass = Grid::class,
    ) {
    }

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
                $newGridConfig[$name] = $this->mergeConfigs($gridConfigs, $grids, $name, $cachedConfigs, $this->defaultClass);
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
