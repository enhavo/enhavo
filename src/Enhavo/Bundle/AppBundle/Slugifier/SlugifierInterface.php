<?php
/**
 * Slugifier.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Slugifier;


interface SlugifierInterface
{
    public static function slugify($content);
}