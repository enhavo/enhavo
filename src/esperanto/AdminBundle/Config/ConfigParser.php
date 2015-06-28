<?php
/**
 * ConfigParser.php
 *
 * @since 01/06/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Config;


use Symfony\Component\HttpFoundation\Request;

class ConfigParser
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $defaultConfig = array();

    /**
     * @var array|null
     */
    protected $mergedConfig = null;

    public function parse(Request $request)
    {
        $config = $request->get('_viewer');
        if($config === null) {
            $config = array();
        }
        $this->config = $config;
        return $this;
    }

    public function setDefault($defaultConfig)
    {
        $this->defaultConfig = $defaultConfig;
    }

    public function get($key)
    {
        $keyArray = preg_split('/\./', $key);

        if($this->mergedConfig === null) {
            $this->mergedConfig = $this->merge($this->defaultConfig, $this->config);
        }

        $value = $this->getByKeyArray($this->mergedConfig, $keyArray);
        return $value;
    }

    public function getType()
    {
        if(!isset($this->config['type'])) {
            return null;
        }
        return $this->config['type'];
    }

    protected function getByKeyArray($config, $keyArray)
    {
        if(empty($keyArray)) {
            return null;
        }

        if(is_array($keyArray)) {
            $key = array_shift($keyArray);
            if(isset($config[$key])) {
                if(count($keyArray) == 0) {
                    return $config[$key];
                } else {
                    return $this->getByKeyArray($config[$key], $keyArray);
                }
            }
        }

        return null;
    }

    protected function merge($default, $config)
    {
        $mergedArray = array();

        foreach($config as $key => $value) {
            $mergedArray[$key] = $value;
        }

        foreach($default as $key => $value) {
            if(array_key_exists($key, $config)) {
                if(is_array($config[$key]) && is_array($value)) {
                    $mergedArray[$key] = $this->merge($value, $config[$key]);
                } else {
                    $mergedArray[$key] = $config[$key];
                }
            } else {
                $mergedArray[$key] = $value;
            }
        }

        return $mergedArray;
    }
}