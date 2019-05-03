<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 08.08.16
 * Time: 14:08
 */

namespace Enhavo\Bundle\RoutingBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlBeautifierExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('url_beautifier',function ($url) {
                $parsedUrl = parse_url($url);
                if(!array_key_exists('scheme', $parsedUrl)){
                    $url = 'http://'.$url;
                }
                return $url;
            })
        );
    }
}
