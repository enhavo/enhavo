<?php

namespace Enhavo\Bundle\VueFormBundle\Exception;

use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;

class VueFormException extends \Exception
{
    public static function missingVueData()
    {
        return new self(sprintf(('Can\'t find vue_data in form vars. Make sure VueTypeExtension was loaded before!')));
    }
}
