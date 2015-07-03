<?php

namespace Enhavo\Bundle\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
class enhavoNewsletterBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
