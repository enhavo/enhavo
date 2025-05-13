<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Twig;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SlugifyExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slugify', [$this, 'render']),
        ];
    }

    public function render($string, $separator = '-')
    {
        return Slugifier::slugify($string, $separator);
    }
}
