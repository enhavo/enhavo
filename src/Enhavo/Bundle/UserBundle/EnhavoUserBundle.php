<?php

namespace Enhavo\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoUserBundle extends Bundle
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