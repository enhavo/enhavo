<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-02
 * Time: 16:20
 */

namespace Enhavo\Bundle\AppBundle\Util;


class StateEncoder
{
    public static function encode($data)
    {
        $state = json_encode($data);
        $state = gzcompress($state);
        $state = base64_encode($state);
        return $state;
    }

    public static function decode($state)
    {
        $state = base64_decode($state);
        $state = gzdecode($state);
        $data = json_decode($state, true);
        return $data;
    }
}
