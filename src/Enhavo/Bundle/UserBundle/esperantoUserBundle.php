<?php

namespace esperanto\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}