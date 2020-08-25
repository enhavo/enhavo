<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\RouteNameGenerator;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;

class TranslationRouteNameGenerator extends RouteNameGenerator
{
    /** @var TranslationManager */
    private $translationManager;

    /** @var RouteTranslator */
    private $routeTranslator;

    /**
     * TranslationRouteNameGenerator constructor.
     * @param TranslationManager $translationManager
     * @param RouteTranslator $routeTranslator
     */
    public function __construct(TranslationManager $translationManager, RouteTranslator $routeTranslator)
    {
        $this->translationManager = $translationManager;
        $this->routeTranslator = $routeTranslator;
    }


    public function generate($resource, $options = [])
    {
        parent::generate($resource, $options);

        $this->generateTranslationRouteNames($resource, $options);
    }

    private function generateTranslationRouteNames($resource, $options = [])
    {
        $locales = $this->translationManager->getLocales();

        foreach ($locales as $locale) {
            if ($locale == $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $route = $this->routeTranslator->getTranslation($resource, $options['route_property'], $locale);

            if ($route) {
                /** @var RouteInterface $route */
                if(!$options['overwrite'] && $route->getName()) {
                    return;
                }
                $route->setName($this->getRandomName());
            }
        }
    }

    public function getType()
    {
        return 'translation_route_name';
    }
}
