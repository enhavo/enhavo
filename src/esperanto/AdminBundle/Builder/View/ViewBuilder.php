<?php
/**
 * ViewBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder\View;

use esperanto\AdminBundle\Admin\Config;

class ViewBuilder
{
    /**
     * @var array
     */
    protected $parameters = array();

    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function processConfig(Config $config)
    {
        foreach($this->parameters as $key => $value) {
            $config->setParameter($key, $value);
        }

        return $config;
    }
}