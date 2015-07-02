<?php

namespace enhavo\CategoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoCategoryBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
