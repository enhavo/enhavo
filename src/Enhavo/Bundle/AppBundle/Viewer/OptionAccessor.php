<?php
/**
 * ConfigParser.php
 *
 * @since 01/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

class OptionAccessor
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $defaultOptions = array();

    public function setDefaults($options)
    {
        $this->defaultOptions = $this->merge($this->defaultOptions, $options);
    }

    public function resolve($options)
    {
        $this->options = $this->merge($this->defaultOptions, $options);
    }

    public function get($key)
    {
        $keyArray = preg_split('/\./', $key);
        $value = $this->getByKeyArray($this->options, $keyArray);
        return $value;
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

    public function toArray()
    {
        return $this->options;
    }
}