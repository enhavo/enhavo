<?php
/**
 * RouteGuesser.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;


use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RouteGuesser
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

    public function getContextProperties($model)
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

    protected function slugify($context)
    {
        $slugifier = new Slugifier();
        return sprintf('/%s', $slugifier->slugify($context));
    }
}