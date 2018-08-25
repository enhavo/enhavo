<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.08.18
 * Time: 02:04
 */

namespace Enhavo\Bundle\SearchBundle\Util;


class TextToWord
{
    public function split($text)
    {
        $split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $text, -1, PREG_SPLIT_NO_EMPTY);
        return $split;
    }
}