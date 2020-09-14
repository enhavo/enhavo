<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 22:34
 */

namespace Enhavo\Bundle\ThemeBundle\Exception;

class ThemeLoadException extends \Exception
{
    static function keyNotExists($file)
    {
        return new static(sprintf('Fail loading theme. Missing key in file "%s"', $file));
    }
}
