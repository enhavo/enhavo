<?php

namespace Enhavo\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoUserBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

}
