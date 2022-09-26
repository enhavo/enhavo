<?php

namespace Enhavo\Bundle\UserBundle\Exception;

class ConfigurationException extends \Exception
{
    public static function configurationNotFound($key)
    {
        return new self(sprintf('Can not find a configuration with key "%s"', $key));
    }

    public static function configKeyNotFound()
    {
        return new self('Can not find any valid key for this request. Add firewall configuratuion or config the route with a config key "_config"');
    }
}
