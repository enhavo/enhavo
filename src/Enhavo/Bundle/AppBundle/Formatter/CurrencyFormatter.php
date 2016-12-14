<?php

namespace Enhavo\Bundle\AppBundle\Formatter;
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 14.12.16
 * Time: 10:46
 */

class CurrencyFormatter
{
    public function getCurrency($value, $currency = 'Euro', $position = 'right')
    {
        $string = (string)$value;
        $string = str_pad($string, 3, '0', STR_PAD_LEFT);
        $length = strlen($string);
        $right = substr($string, $length - 2, 2 );
        $left = substr($string, 0, $length - 2);
        if($position == 'right'){
            return sprintf('%s,%s %s', $left, $right, $currency);
        } else {
            return sprintf('%s %s,%s', $currency,$left, $right);
        }
    }
}