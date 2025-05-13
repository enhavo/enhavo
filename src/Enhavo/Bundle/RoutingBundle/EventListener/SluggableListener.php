<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;

/*
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
