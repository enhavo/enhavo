<?php
/**
 * RouteGuesser.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;


use BaconStringUtils\Slugifier;

class RouteGuesser
{
    public function guessUrl($model)
    {
        $guesses = [];
        if(method_exists($model, 'getTitle')) {
            $guesses[] = $model->getTitle();
        }

        if(method_exists($model, 'getName')) {
            $guesses[] = $model->getName();
        }

        if(method_exists($model, 'getSlug')) {
            $guesses[] = $model->getSlug();
        }

        if(method_exists($model, 'getHeadline')) {
            $guesses[] = $model->getHeadline();
        }

        foreach($guesses as $guess) {
            if(!empty($guess)) {
                return $this->slugifiy($guess);
            }
        }
        return null;
    }

    protected function slugifiy($guess)
    {
        //ToDo: Because of translation we need to check for array, maybe its better to overwrite this class in TransaltionBundle
        if(is_array($guess)) {
            $guess = reset($guess);
        }
        $slugifier = new Slugifier();
        return sprintf('/%s', $slugifier->slugify($guess));
    }
}