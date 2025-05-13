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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlBeautifierExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('url_beautifier', function ($url) {
                $parsedUrl = parse_url($url);
                if (!array_key_exists('scheme', $parsedUrl)) {
                    $url = 'http://'.$url;
                }

                return $url;
            }),
        ];
    }
}
