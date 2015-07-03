<?php

namespace Enhavo\Bundle\SettingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoSettingBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
