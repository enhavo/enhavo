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
        $context = $this->guessContext($model);
        if($context !== null) {
            return $this->slugifiy($context);
        }
        return $context;
    }

    public function guessContext($model)
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
                return $guess;
            }
        }
        return null;
    }

    protected function slugifiy($context)
    {
        $slugifier = new Slugifier();
        return sprintf('/%s', $slugifier->slugify($context));
    }
}