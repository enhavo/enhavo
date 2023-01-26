<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.17
 * Time: 15:36
 */

namespace Enhavo\Bundle\RoutingBundle\Twig;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SlugifyExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('slugify', array($this, 'render')),
        );
    }

    public function render($string, $separator = '-')
    {
        return Slugifier::slugify($string, $separator);
    }
}
