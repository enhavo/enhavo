<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 20:00
 */

namespace Enhavo\Bundle\AppBundle\Routing\Generator;


class GuessGenerator
{
    public function guessUrl($model)
    {
        $context = $this->guessContext($model);
        if($context !== null) {
            return $this->slugify($context);
        }
        return $context;
    }

    public function guessContext($model)
    {
        $guesses = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        $properties = $this->getContextProperties($model);
        foreach($properties as $property) {
            $guesses[] = $accessor->getValue($model, $property);
        }

        foreach($guesses as $guess) {
            if(!empty($guess)) {
                return $guess;
            }
        }
        return null;
    }

    private function getContextProperties($model)
    {
        $possibleProperties = [];
        $checkProperties = [
            'title',
            'name',
            'headline',
            'slug'
        ];

        foreach($checkProperties as $property) {
            $method = sprintf('get%s', ucfirst($property));
            if(method_exists($model, $method)) {
                $possibleProperties[] = $property;
            }
        }

        return $possibleProperties;
    }

    private function slugify($context)
    {
        $slugifier = new Slugifier();
        return sprintf('/%s', $slugifier->slugify($context));
    }
}