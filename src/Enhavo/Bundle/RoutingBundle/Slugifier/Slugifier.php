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

use Symfony\Component\String\Slugger\AsciiSlugger;

class Slugifier implements SlugifierInterface
{
    private static ?AsciiSlugger $slugger = null;

    public static function slugify($content, $separator = '-')
    {
        // Remove apostrophes between word characters (e.g. "don't" => "dont") to keep existing slugs stable
        $content = preg_replace('/(\w)\'(\w)/u', '${1}${2}', (string) $content);

        // The "de" locale transliterates umlauts and ß (e.g. "Übel weiß" => "uebel-weiss")
        self::$slugger ??= new AsciiSlugger('de');

        return self::$slugger->slug($content, $separator)->lower()->toString();
    }
}
