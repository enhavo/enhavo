<?php

namespace esperanto\SliderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class esperantoSliderBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
