<?php

namespace Enhavo\Bundle\SliderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class enhavoSliderBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
