<?php

namespace enhavo\SliderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoSliderBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
