<?php
/**
 * ContextFinder.php
 *
 * @since 20/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;


use Enhavo\Bundle\TranslationBundle\Translator\Strategy\TranslationTableStrategy;

class ContextFinder
{
    public function __construct(TranslationTableStrategy $translationTableStrategy)
    {

    }

    protected function find($subject, $locale, $hints = [])
    {
        $context = $this->routeGuesser->guessContext($subject);

        if(is_array($context)) {
            $newTitle = null;
            foreach($context as $titleLocale => $value) {
                if(!empty($value) && empty($newTitle)) {
                    $newTitle = $value;
                }
                if($titleLocale === $locale && !empty($value)) {
                    $newTitle = $value;
                }
            }
            if(empty($newTitle)) {
                $newTitle = microtime(true);
            }
            return $newTitle;
        }

        return $context;
    }
}