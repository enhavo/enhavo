<?php
/**
 * SlugableListener.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;

/**
 * We use this class only if Gedmo was included
 */
if (class_exists('Gedmo\Sluggable\SluggableListener')) {
    class SluggableListener extends \Gedmo\Sluggable\SluggableListener
    {
        public function __construct()
        {
            parent::__construct();
            $this->setTransliterator([$this, 'transliterate']);
        }

        public function transliterate($text, $separator = '-')
        {
            return Slugifier::slugify($text, $separator);
        }
    }
}
