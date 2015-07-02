<?php

namespace esperanto\NewsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoNewsBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
