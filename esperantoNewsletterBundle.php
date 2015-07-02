<?php

namespace esperanto\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
class esperantoNewsletterBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
