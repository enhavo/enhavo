<?php

namespace enhavo\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoPageBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
