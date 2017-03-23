<?php
/**
 * RouteGuesser.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Enhavo\Bundle\AppBundle\Route\RouteGuesser as AppRouteGuesser;
use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;

class RouteGuesser extends AppRouteGuesser
{
    protected function slugify($guess)
    {
        $slugifier = new Slugifier();
        if(is_array($guess)) {
            foreach($guess as $key => $value) {
                $guess[$key] = sprintf('/%s', $slugifier->slugify($value));
            }
            return $guess;
        }
        return sprintf('/%s', $slugifier->slugify($guess));
    }
}