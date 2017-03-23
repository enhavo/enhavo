<?php
/**
 * Slugifier.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Slugifier;


use Gedmo\Sluggable\Util\Urlizer;

class Slugifier implements SlugifierInterface
{
    public static function slugify($content)
    {
        $urlizer = new Urlizer();
        return $urlizer->urlize($content);
    }
}