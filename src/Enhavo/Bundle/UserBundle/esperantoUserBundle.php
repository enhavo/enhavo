<?php

namespace enhavo\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoUserBundle extends Bundle
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