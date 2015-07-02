<?php

namespace enhavo\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
class enhavoNewsletterBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
