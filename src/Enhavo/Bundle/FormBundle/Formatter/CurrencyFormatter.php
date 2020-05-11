<?php

namespace Enhavo\Bundle\FormBundle\Formatter;
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 14.12.16
 * Time: 10:46
 */

class CurrencyFormatter
{
    public function getCurrency($currencyAsInt, $currency = 'Euro', $position = 'right')
    {
        $string = (string)$currencyAsInt;
        $string = str_pad($string, 3, '0', STR_PAD_LEFT);
        $length = strlen($string);
        $right = substr($string, $length - 2, 2 );
        $left = substr($string, 0, $length - 2);
        if ($currency !== null) {
            if ($position == 'right') {
                return sprintf('%s,%s %s', $left, $right, $currency);
            } else {
                return sprintf('%s %s,%s', $currency, $left, $right);
            }
        } else {
            return sprintf('%s,%s', $left, $right);
        }
    }

    public function getInt($currencyAsString)
    {
        //text -> int
        $string = $currencyAsString;
        $string = str_replace('.', '', $string);

        $parts = explode(',', $string);
        $right = 0;
        if (count($parts) > 1) {
            $right = array_pop($parts);
            $right = substr($right, 0, 2);
            $right = str_pad($right, 2, '0');
            $right = intval($right);
        }

        $left = implode($parts);
        $left = intval($left);

        $value = $right;
        if ($left > 0) {
            $value = $left * 100 + $value;
        }

        return $value;
    }
}
