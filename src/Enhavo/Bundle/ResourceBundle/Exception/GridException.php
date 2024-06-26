<?php

namespace Enhavo\Bundle\ResourceBundle\Exception;

use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\Grid\GridInterface;

class GridException extends \Exception
{
    public static function missingService($service): self
    {
        return new self(sprintf('Service "%s" not exists in container!', $service));
    }

    public static function configurationNotExits($key): self
    {
        return new self(sprintf('Grid configuration with key "%s" not exists!', $key));
    }

    public static function notImplementGridInterface($class): self
    {
        return new self(sprintf('The class "%s" doesn\'t implement interface %s!', get_class($class), GridInterface::class));
    }
}
