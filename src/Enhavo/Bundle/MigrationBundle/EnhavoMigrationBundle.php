<?php

namespace Enhavo\Bundle\MigrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMigrationBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
