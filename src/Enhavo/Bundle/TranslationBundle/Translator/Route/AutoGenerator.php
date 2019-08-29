<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Enhavo\Bundle\AppBundle\Routing\AutoGenerator as AppAutoGenerator;
use Enhavo\Bundle\AppBundle\Routing\GeneratorInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Entity\Route;

class AutoGenerator extends AppAutoGenerator
{
    protected function updateRoute(GeneratorInterface $generator, Routeable $routeable)
    {
        $staticPrefix = $this->generateWithLocale($generator, $routeable, $this->getDefaultLocale());
        $staticPrefix = sprintf('/%s%s', $this->getDefaultLocale(), $staticPrefix);

        $route = $routeable->getRoute();
        if($route instanceof Route) {
            $route->setStaticPrefix($staticPrefix);
        } else {
            $route = $this->routeManager->createRoute($routeable, $staticPrefix);
        }

        $this->em->flush();
        $this->routeManager->update($route);
        $this->em->flush();

        $locales = $this->getTranslationRouteLocales();
        foreach($locales as $locale) {
            $this->updateTranslationRoute($generator, $routeable, $locale);
        }
    }

    private function updateTranslationRoute(GeneratorInterface $generator, Routeable $routeable, $locale)
    {
        $staticPrefix = $this->generateWithLocale($generator, $routeable, $locale);
        $staticPrefix = sprintf('/%s%s', $locale, $staticPrefix);
        
        $this->getTranslationRouteManager()->updateTranslationRoute($routeable,  $locale, $staticPrefix);
    }

    private function getDefaultLocale()
    {
        return $this->container->getParameter('enhavo_translation.default_locale');
    }

    private function getTranslationRouteManager()
    {
        return $this->container->get('enhavo_translation.translation_route_manager');
    }

    private function getTranslationRouteLocales()
    {
        $translationLocales = [];
        $defaultLocale = $this->getDefaultLocale();
        $locales = $this->container->getParameter('enhavo_translation.locales');
        foreach($locales as $local => $values) {
            if($local != $defaultLocale) {
                $translationLocales[] = $local;
            }
        }
        return $translationLocales;
    }

    private function generateWithLocale(GeneratorInterface $generator, $routeable, $locale)
    {
        if($generator instanceof LocaleGenerator) {
            return $generator->generate($routeable, $locale);
        }
        return $generator->generate($routeable);
    }
}