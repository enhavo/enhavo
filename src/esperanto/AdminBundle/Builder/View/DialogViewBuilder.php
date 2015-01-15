<?php
/**
 * DialogViewBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder\View;

use esperanto\AdminBundle\Admin\Config;

class DialogViewBuilder extends ViewBuilder
{
    /**
     * @var array
     */
    protected $tabs = array();

    public function setTab($name, $label, $template)
    {
        $this->tabs[$name] = array(
            'label' => $label,
            'template' => $template
        );
    }

    public function processConfig(Config $config)
    {
        $config = parent::processConfig($config);

        foreach($this->tabs as $key => $value) {
            $config->setTab($key, $value['label'], $value['template']);
        }
        return $config;
    }
}