<?php

namespace esperanto\ReferenceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoReferenceBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
