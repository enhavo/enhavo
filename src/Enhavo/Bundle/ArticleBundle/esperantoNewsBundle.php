<?php

namespace Enhavo\Bundle\NewsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoNewsBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
