<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.17
 * Time: 15:36
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;

class Slugify extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFunction('slugify', array($this, 'slugify')),
        );
    }

    public function render($string)
    {
        return Slugifier::slugify($string);
    }

    public function getName()
    {
        return 'slugify';
    }
}