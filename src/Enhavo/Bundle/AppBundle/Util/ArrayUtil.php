<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-06
 * Time: 21:57
 */

namespace Enhavo\Bundle\AppBundle\Util;


class ArrayUtil
{
    public static function merge($array1, $array2)
    {
        $result = array();
        foreach($array1 as $key => $value) {
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
