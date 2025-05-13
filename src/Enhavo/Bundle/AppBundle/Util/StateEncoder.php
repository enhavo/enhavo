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
        $state = gzuncompress($state);
        $data = json_decode($state, true);

        return $data;
    }
}
