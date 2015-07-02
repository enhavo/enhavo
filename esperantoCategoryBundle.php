<?php

namespace esperanto\CategoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoCategoryBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
