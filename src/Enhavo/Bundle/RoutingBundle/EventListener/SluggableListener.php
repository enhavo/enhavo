<?php
/**
 * SlugableListener.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Gedmo\Sluggable\SluggableListener as GedmoSluggableListener;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;

class SluggableListener extends GedmoSluggableListener
{
    public function __construct()
    {
        parent::__construct();
        $this->setTransliterator(array(SluggableListener::class, 'transliterate'));
    }

    /**
     * @param string $text
     * @param string $separator
     * @return string $text
     */
    public static function transliterate($text, $separator = '-')
    {
        return Slugifier::slugify($text, $separator);
    }
}