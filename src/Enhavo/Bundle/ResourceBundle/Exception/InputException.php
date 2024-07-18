<?php

namespace Enhavo\Bundle\ResourceBundle\Exception;

use Enhavo\Bundle\ResourceBundle\Input\InputInterface;

class InputException extends \Exception
{
    public static function missingService($service): self
    {
        return new self(sprintf('Service "%s" not exists in container!', $service));
    }

    public static function configurationNotExits($key): self
    {
        return new self(sprintf('Input configuration with key "%s" not exists!', $key));
    }

    public static function notImplementInputInterface($class): self
    {
        return new self(sprintf('The class "%s" doesn\'t implement interface %s!', get_class($class), InputInterface::class));
    }
}
