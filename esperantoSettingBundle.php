<?php

namespace esperanto\SettingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoSettingBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
