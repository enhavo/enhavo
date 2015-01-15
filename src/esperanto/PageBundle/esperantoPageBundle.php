<?php

namespace esperanto\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoPageBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
