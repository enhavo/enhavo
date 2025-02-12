<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;

abstract class AbstractConfigurationMerger
{
    abstract public function performMerge(array $configs): array;

    protected function mergeConfigs(array $configs, array $allConfigs, string $name, array &$cachedConfigs, string $defaultClass): array
    {
        if (isset($cachedConfigs[$name])) {
            return $cachedConfigs[$name];
        }

        usort($configs, function ($a, $b) {
            $aPriority = $a['priority'] ?? 0;
            $bPriority = $b['priority'] ?? 0;

            return $aPriority - $bPriority;
        });

        $beforeConfig = null;

        foreach ($configs as $config) {

            $extends = $config['extends'] ?? null;
            $overwrite = $config['overwrite'] ?? false;

            $config = $this->sanitizeConfig($config);

            // set class
            if (!array_key_exists('class', $config)) {
                $config['class'] = $beforeConfig !== null ? $beforeConfig['class'] : $defaultClass;
            }
            
            // extends target config
            if (isset($extends)) {
                if (!array_key_exists($extends, $allConfigs)) {
                    throw new \Exception(sprintf('Try to extend from "%s" in "%s" but "%s" doesn\'t exist.', $extends, $name, $extends));
                }
                $parentGridConfig = $this->mergeConfigs($allConfigs[$extends], $allConfigs, $extends, $cachedConfigs, $defaultClass);
                $config['class'] = $config['class'] ?? $parentGridConfig['class'];
                if ($this->isCallable($config, $overwrite)) {
                    $config = $config['class']::mergeConfigs($parentGridConfig, $config);
                } else {
                    $config = array_merge($parentGridConfig, $config);
                }
            }

            // extends with same name
            if ($beforeConfig !== null) {
                if ($this->isCallable($config, $overwrite)) {
                    $config = $config['class']::mergeConfigs($beforeConfig, $config);
                }
            }
            
            $beforeConfig = $config;
        }

        $cachedConfigs[$name] = $beforeConfig;

        return $beforeConfig;
    }

    private function sanitizeConfig($config)
    {
        if (array_key_exists('priority', $config)) {
            unset($config['priority']);
        }

        if (array_key_exists('overwrite', $config)) {
            unset($config['overwrite']);
        }

        if (array_key_exists('extends', $config)) {
            unset($config['extends']);
        }

        return $config;
    }

    private function isCallable($config, bool $overwrite): bool
    {
        if ($overwrite === true) {
            return false;
        }

        if (!isset($config['class'])) {
            return false;
        }

        if (is_subclass_of($config['class'], ConfigMergeInterface::class)) {
            return true;
        }

        return false;
    }
}
