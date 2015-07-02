<?php

namespace Enhavo\Bundle\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoPageBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
