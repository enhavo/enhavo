<?php

namespace Enhavo\Bundle\UserBundle\Exception;

use Symfony\Component\HttpFoundation\Request;

class ConfigurationException extends \Exception
{
    public static function configurationNotFound($key, $section)
    {
        return new self(sprintf('Can not find configuration in section "%s" for key "%s". Maybe you forgot to config this section', $section, $key));
    }

    public static function configKeyNotFound(Request $request)
    {
        return new self(sprintf('Can not find a valid key in _config attribute in route "%s". The key must be set to fetch the configuration', $request->attributes->get('_route')));
    }
}
