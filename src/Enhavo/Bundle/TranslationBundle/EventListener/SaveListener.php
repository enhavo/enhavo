<?php

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Route\Slugable;
use Enhavo\Bundle\TranslationBundle\Route\RouteGuesser;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use BaconStringUtils\Slugifier;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Symfony\Component\EventDispatcher\GenericEvent;

class SaveListener
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var RouteGuesser
     */
    protected $routeGuesser;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param Translator $translator
     * @param RouteGuesser $routeGuesser
     */
    public function __construct(Translator $translator, RouteGuesser $routeGuesser)
    {
        $this->translator = $translator;
        $this->routeGuesser = $routeGuesser;
    }

    public function postSave()
    {
        $this->translator->updateReferences();
    }

    public function preSave(GenericEvent $event)
    {
        $subject = $event->getSubject();

        if($subject instanceof Slugable) {
            $slug = $subject->getSlug();
            if (is_array($slug)) {
                foreach ($slug as $locale => $value) {
                    if (empty($value)) {
                        $slug[$locale] = $this->slugify($this->getContext($subject, $locale));
                    }
                }
                $subject->setSlug($slug);
            }
        }

        if($subject instanceof Routeable) {
            /** @var Route $route */
            $route = $subject->getRoute();
            if($route instanceof Route) {
                $staticPrefixes = $route->getStaticPrefix();
                if(is_array($staticPrefixes)) {
                    foreach($staticPrefixes as $locale => $value) {
                        if(empty($value)) {
                            $staticPrefixes[$locale] = sprintf('/%s/%s', $locale, $this->slugify($this->getContext($subject, $locale)));
                        }
                    }
                    $route->setStaticPrefix($staticPrefixes);
                }
            }
        }
    }

    protected function getContext($subject, $locale)
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

    protected function slugify($text)
    {
        return (new Slugifier())->slugify($text);
    }
}