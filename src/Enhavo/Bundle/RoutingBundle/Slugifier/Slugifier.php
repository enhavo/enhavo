<?php
/**
 * Slugifier.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Slugifier;

class Slugifier implements SlugifierInterface
{
    public static function slugify($content, $separator = '-')
    {
        $urlizer = new Urlizer();
        $content = $urlizer->urlize($content, $separator);
        $content  = $urlizer->transliterate($content, $separator);
        return $content;
    }
}
