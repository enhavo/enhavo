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

use Enhavo\Bundle\ResourceBundle\Input\Input;

class InputConfigurationMerger extends AbstractConfigurationMerger
{
    public function __construct(
        protected string $defaultClass = Input::class,
    ) {
    }

    public function performMerge(array $configs): array
    {
        $inputs = [];

        foreach ($configs as $config) {
            if (isset($config['inputs'])) {
                foreach ($config['inputs'] as $name => $inputConfig) {
                    if (!isset($inputs[$name])) {
                        $inputs[$name] = [];
                    }
                    $inputs[$name][] = $inputConfig;
                }

                unset($config['inputs']);
            }
        }

        $newInputConfig = [];
        $cachedConfigs = [];
        foreach ($inputs as $name => $inputConfigs) {
            try {
                $newInputConfig[$name] = $this->mergeConfigs($inputConfigs, $inputs, $name, $cachedConfigs, $this->defaultClass);
            } catch (\Exception $exception) {
                throw new \Exception(sprintf('Error merging input configs: %s', $exception->getMessage()), 0, $exception);
            }
        }

        $configs[] = [
            'inputs' => $newInputConfig,
        ];

        return $configs;
    }
}
