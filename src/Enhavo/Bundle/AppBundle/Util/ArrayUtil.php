<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Util;

class ArrayUtil
{
    public static function merge($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            if (isset($array2[$key])) {
                if (is_array($value)) {
                    if (is_array($array2[$key])) {
                        $result[$key] = self::merge($value, $array2[$key]);
                    } else {
                        $result[$key] = $array2[$key];
                    }
                } else {
                    $result[$key] = $array2[$key];
                }
            } else {
                $result[$key] = $value;
            }
        }
        foreach ($array2 as $key => $value) {
            if (!isset($result[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
