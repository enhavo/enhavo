<?php
/**
 * ConfigParser.php
 *
 * @since 01/06/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


use Symfony\Component\HttpFoundation\Request;

class ConfigParser
{
    protected $config;

    public function parse(Request $request)
    {
        $this->config = $request->get('_viewer');
        return $this;
    }

    public function get($key)
    {
        $keyArray = preg_split('/\./', $key);
        return $this->getByKeyArray($this->config, $keyArray);
    }

    public function getType()
    {
        return $this->config['type'];
    }

    public function getByKeyArray($config, $keyArray)
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
}