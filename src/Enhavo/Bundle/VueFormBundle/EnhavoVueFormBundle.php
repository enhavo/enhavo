<?php

namespace Enhavo\Bundle\VueFormBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoVueFormBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
