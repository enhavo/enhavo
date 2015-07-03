<?php

namespace Enhavo\Bundle\CategoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoCategoryBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
