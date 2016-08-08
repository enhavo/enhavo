<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 08.08.16
 * Time: 14:08
 */

namespace Enhavo\Bundle\AppBundle\Twig;


class UrlBeautifier extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_beautifier',function ($url) {
                $parsedUrl = parse_url($url);
                if(!array_key_exists('scheme', $parsedUrl)){
                    $url = 'http://'.$url;
                }
                return $url;
            })
        );
    }

    public function getName()
    {
        return 'url_beautifier';
    }
}