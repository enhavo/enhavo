<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;

class InputConfigurationMerger extends AbstractConfigurationMerger
{
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
                $newInputConfig[$name] = $this->mergeConfigs($inputConfigs, $inputs, $name, $cachedConfigs);
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
