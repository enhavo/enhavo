<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-03-16
 * Time: 11:04
 */

namespace Enhavo\Bundle\FormBundle\Formatter;


class HtmlSanitizer
{
    public function sanitize($value, $options = [])
    {
        if(empty($value)) {
            return $value;
        }

        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($value);
    }
}
