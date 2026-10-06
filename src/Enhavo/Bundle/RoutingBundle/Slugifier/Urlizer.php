<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Slugifier;

/**
 * @deprecated Use Slugifier::slugify() instead
 */
class Urlizer
{
    public static function urlize($text, $separator = '-')
    {
        return Slugifier::slugify($text, $separator);
    }

    public static function transliterate($text, $separator = '-')
    {
        return Slugifier::slugify($text, $separator);
    }
}
