<?php

namespace Enhavo\Bundle\VueFormBundle\Exception;

use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;

class VueTypeException extends \Exception
{
    public static function invalidInterface($class)
    {
        return new self(sprintf('"%s" not implementing interface "%s"', $class, VueTypeInterface::class));
    }

    public static function locked()
    {
        return new self('A form was already created. Can\'t register further VueTypes');
    }
}
