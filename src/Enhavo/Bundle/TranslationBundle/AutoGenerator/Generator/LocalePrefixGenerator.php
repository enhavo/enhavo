<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-15
 * Time: 16:58
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalePrefixGenerator extends AbstractGenerator
{
    public function __construct(
        private TranslationManager $translationManager,
        private TranslatorInterface $routeTranslator,
        private TranslatorInterface $textTranslator,
        private RouteFactory $routeFactory,
    )
    {
    }


    public function generate($resource, $options = [])
    {
        if ($options['generate_default']) {
            $this->generateDefaultRoute($resource, $options);
        }

        if ($options['generate_translations']) {
            $this->generateTranslationRoutes($resource, $options);
        }
    }

    private function generateDefaultRoute($resource, $options = [])
    {
        $locale = $this->translationManager->getDefaultLocale();

        $value = $value = $this->getProperty($resource, $options['property']);
        if ($value !== null) {
            $route = $this->getProperty($resource, $options['route_property']);
            if (!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }

            if ($options['default_prefix_locale']) {
                $route->setStaticPrefix($this->createLocalePrefix($locale, $value));
            } else {
                $route->setStaticPrefix($this->createPrefix($value));
            }
        }
    }

    private function generateTranslationRoutes($resource, $options)
    {
        $locales = $this->translationManager->getLocales();

        foreach ($locales as $locale) {
            if ($locale == $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $route = $this->routeTranslator->getTranslation($resource, $options['route_property'], $locale)
                ?? $this->routeFactory->createNew();
            if (!$options['overwrite'] && $route->getStaticPrefix()) {
                continue;
            }

            $value = $this->textTranslator->getTranslation($resource, $options['property'], $locale)
                ?? ($options['allow_fallback'] ? $this->getProperty($resource, $options['property']) : null);

            if (null !== $value) {
                if ($options['translation_prefix_locale']) {
                    $route->setStaticPrefix($this->createLocalePrefix($locale, $value));
                } else {
                    $route->setStaticPrefix($this->createPrefix($value));
                }

                $this->routeTranslator->setTranslation($resource, $options['route_property'], $locale, $route);
            }
        }
    }

    private function createPrefix($value)
    {
        return sprintf('/%s', Slugifier::slugify($value));
    }

    private function createLocalePrefix($locale, $value)
    {
        return sprintf('/%s/%s', $locale, Slugifier::slugify($value));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'overwrite' => false,
            'route_property' => 'route',
            'allow_fallback' => true,
            'generate_default' => true,
            'generate_translations' => true,
            'default_prefix_locale' => true,
            'translation_prefix_locale' => true
        ]);
        $resolver->setRequired('property');
    }

    public function getType()
    {
        return 'locale_prefix';
    }
}
