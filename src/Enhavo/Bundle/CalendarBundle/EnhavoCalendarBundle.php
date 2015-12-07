<?php

namespace Enhavo\Bundle\CalendarBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoCalendarBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
