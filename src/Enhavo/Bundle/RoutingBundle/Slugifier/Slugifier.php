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

class Slugifier implements SlugifierInterface
{
    public static function slugify($content, $separator = '-')
    {
        $urlizer = new Urlizer();
        $content = $urlizer->urlize($content, $separator);
        $content = $urlizer->transliterate($content, $separator);

        return $content;
    }
}
