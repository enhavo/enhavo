<?php
/**
 * WysiwygConfig.php
 *
 * @since 07/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Form\Config;

use Symfony\Component\Yaml\Yaml;

class WysiwygConfig
{
    /**
     * @var array
     */
    private $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function getData(WysiwygOption $option)
    {
        $data = array(
            'formats' => $this->getConfig('formats'),
            'plugins' => $this->getConfig('plugins'),
            'toolbar1' => $option->getToolbar1($this->getConfig('toolbar1')),
            'toolbar2' => $option->getToolbar2($this->getConfig('toolbar2')),
            'height' => $option->getHeight($this->getConfig('height')),
            'style_formats' => $option->getFormats($this->getConfig('style_formats')),
            'content_css' => $option->getContentCss($this->getConfig('content_css')),
        );
        return json_encode($data);
    }

    private function getConfig($name, $default = null)
    {
        if(isset($this->config[$name])) {
            return $this->config[$name];
        }
        return $default;
    }
}
