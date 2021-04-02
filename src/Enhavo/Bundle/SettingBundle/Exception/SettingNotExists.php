<?php

namespace Enhavo\Bundle\SettingBundle\Exception;

class SettingNotExists extends \Exception
{
    public static function keyNotFound($key) {
        return new self(sprintf('Setting with key "%s" is not configured', $key));
    }

    public static function entityNotFound() {
        return new self('Setting config exists, but no entity found. Maybe you forgot to execute bin/console enhavo:init');
    }
}
