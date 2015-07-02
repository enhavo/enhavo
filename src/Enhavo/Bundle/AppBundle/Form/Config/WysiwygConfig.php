<?php
/**
 * WysiwygConfig.php
 *
 * @since 07/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AdminBundle\Form\Config;

use Symfony\Component\Yaml\Yaml;

class WysiwygConfig
{
    protected $configPath;

    protected $config;

    public function __construct($configPath = null)
    {
        if(file_exists($configPath)) {
            $this->config = Yaml::parse($configPath);
        }
    }

    public function getData(WysiwygOption $option)
    {
        $data = array(
            'formats' => $this->getConfig('formats'),
            'toolbar1' => $option->getToolbar1($this->getConfig('toolbar1')),
            'toolbar2' => $option->getToolbar2($this->getConfig('toolbar2')),
            'height' => $option->getHeight($this->getConfig('height')),
            'style_formats' => $option->getFormats($this->getConfig('style_formats')),
            'content_css' => $option->getContentCss($this->getConfig('content_css')),
        );
        return json_encode($data);
    }

    protected function getConfig($name, $default = null)
    {
        if(isset($this->config[$name])) {
            return $this->config[$name];
        }
        return $default;
    }
}