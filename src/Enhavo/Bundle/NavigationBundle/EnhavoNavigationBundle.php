<?php

namespace Enhavo\Bundle\NavigationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoNavigationBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
