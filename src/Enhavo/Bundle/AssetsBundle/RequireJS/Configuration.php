<?php


namespace Enhavo\Bundle\AssetsBundle\RequireJS;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Configuration.php
 *
 * @since 15/07/16
 * @author gseidel
 */
class Configuration
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var AssetsHelper
     */
    private $assetsHelper;

    public function __construct($configuration, AssetsHelper $assetsHelper)
    {
        $this->configuration = $configuration;
        $this->assetsHelper = $assetsHelper;
    }

    public function getConfiguration()
    {
        $config =  [
            'paths' => [],
            'shim' => [],
            'waitSeconds' => 200
        ];

        if(isset($this->configuration['paths']) && is_array($this->configuration['paths'])) {
            foreach($this->configuration['paths'] as $name => $path) {
                $config['paths'][$name] = $path['location'];
            }

            foreach($this->configuration['paths'] as $name => $path) {
                $config['shim'][$name] = [];
                if(isset($path['exports'])) {
                    $config['shim'][$name]['exports'] = $path['exports'];
                }
                if(isset($path['dependencies'])) {
                    $config['shim'][$name]['deps'] = $path['dependencies'];
                }
            }
        }


        if($this->assetsHelper->getVersion()) {
            $config['urlArgs'] = $this->assetsHelper->getVersion();
        }

        return $config;
    }
}