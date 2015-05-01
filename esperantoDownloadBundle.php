<?php

namespace esperanto\DownloadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoDownloadBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
