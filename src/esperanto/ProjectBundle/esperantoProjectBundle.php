<?php

namespace esperanto\ProjectBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoProjectBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
