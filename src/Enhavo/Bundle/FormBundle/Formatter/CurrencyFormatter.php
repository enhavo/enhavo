<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $presign = $currencyAsInt < 0 ? '-' : '';
        $string = (string) abs($currencyAsInt);
        $string = str_pad($string, 3, '0', STR_PAD_LEFT);
        $length = strlen($string);
        $right = substr($string, $length - 2, 2);
        $left = substr($string, 0, $length - 2);
        if (null !== $currency) {
            if ('right' == $position) {
                return sprintf('%s%s,%s %s', $presign, $left, $right, $currency);
            }

            return sprintf('%s %s%s,%s', $currency, $presign, $left, $right);
        }

        return sprintf('%s%s,%s', $presign, $left, $right);
    }

    public function getInt($currencyAsString)
    {
        // text -> int
        $string = $currencyAsString;
        $multiplier = 1;

        if ('-' === substr($string, 0, 1)) {
            $multiplier = -1;
            $string = substr($string, 1);
        }

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

        return $multiplier * $value;
    }
}
