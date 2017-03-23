<?php
/**
 * RouteGuessGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;

class RouteGuessGenerator implements LocaleGenerator
{
    /**
     * @var string
     */
    protected $routeGuesser;

    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(RouteGuesser $routeGuesser, Translator $translator)
    {
        $this->routeGuesser = $routeGuesser;
        $this->translator = $translator;
    }

    public function generate(Routeable $routeable, $locale = null)
    {
        $properties = $this->routeGuesser->getContextProperties($routeable);
        
        foreach($properties as $property) {
            $translation = $this->translator->getTranslation($routeable, $property, $locale);
            if(!empty($translation)) {
                return $this->slugify($translation);
            }
        }

        return $this->routeGuesser->guessUrl($routeable);
    }

    protected function slugify($context)
    {
        $slugifier = new Slugifier();
        return sprintf('/%s', $slugifier->slugify($context));
    }
}