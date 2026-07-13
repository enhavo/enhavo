<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\RoutingBundle\Util\UniquePrefixGenerator;
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
        private UniquePrefixGenerator $uniquePrefixGenerator,
    ) {
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

        $value = $this->getProperty($resource, $options['property']);
        if (null !== $value) {
            $route = $this->getProperty($resource, $options['route_property']);
            if (!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }

            if ($options['default_prefix_locale']) {
                $route->setStaticPrefix($this->createPrefix($value, $resource, $options, $locale));
            } else {
                $route->setStaticPrefix($this->createPrefix($value, $resource, $options));
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
                    $route->setStaticPrefix($this->createPrefix($value, $resource, $options, $locale));
                } else {
                    $route->setStaticPrefix($this->createPrefix($value, $resource, $options));
                }

                $this->routeTranslator->setTranslation($resource, $options['route_property'], $locale, $route);
            }
        }
    }

    private function createPrefix($value, $resource, $options, ?string $locale = null): string
    {
        if (null !== $locale) {
            $properties = ['locale' => $locale, 'value' => Slugifier::slugify($value)];
            $format = '/{locale}/{value}';
        } else {
            $properties = ['value' => Slugifier::slugify($value)];
            $format = '/{value}';
        }

        return $this->uniquePrefixGenerator->generate($properties, $resource, [
            'format' => $format,
            'max_length' => $options['max_length'],
        ]);
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
            'translation_prefix_locale' => true,
            'max_length' => 255,
        ]);
        $resolver->setRequired('property');
    }

    public function getType()
    {
        return 'locale_prefix';
    }
}
