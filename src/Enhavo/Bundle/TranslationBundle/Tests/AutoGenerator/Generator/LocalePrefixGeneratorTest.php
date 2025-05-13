<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;
use Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator\LocalePrefixGenerator;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\RouteableMock;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalePrefixGeneratorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new LocalePrefixGeneratorTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->routeTranslator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->textTranslator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->routeFactory = $this->getMockBuilder(RouteFactory::class)->getMock();
        $dependencies->routeFactory->method('createNew')->willReturn(new Route());

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        return new LocalePrefixGenerator(
            $dependencies->translationManager,
            $dependencies->routeTranslator,
            $dependencies->textTranslator,
            $dependencies->routeFactory
        );
    }

    public function testConfigureOptions()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
        $resolver = new OptionsResolver();
        $instance->configureOptions($resolver);
        $options = $resolver->getDefinedOptions();
        $assert = [
            'property',
            'overwrite',
            'route_property',
            'allow_fallback',
            'generate_default',
            'generate_translations',
            'default_prefix_locale',
            'translation_prefix_locale',
        ];
        sort($options);
        sort($assert);

        $this->assertEquals($assert, $options);
    }

    public function testGenerateDefault()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $instance = $this->createInstance($dependencies);

        $entity = new RouteableMock();
        $entity->setRoute($dependencies->routeFactory->createNew());
        $entity->setName('harry');

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => true,
            'overwrite' => false,
            'generate_translations' => false,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => false,
        ]);
        $route = $entity->getRoute();
        $this->assertEquals('/harry', $route->getStaticPrefix());

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => true,
            'overwrite' => true,
            'generate_translations' => false,
            'default_prefix_locale' => true,
            'translation_prefix_locale' => false,
        ]);

        $this->assertEquals('/pl/harry', $route->getStaticPrefix());

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => true,
            'overwrite' => false,
            'generate_translations' => false,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => false,
        ]);

        $this->assertEquals('/pl/harry', $route->getStaticPrefix());
    }

    public function testGenerateTranslationsNoPrefix()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) use ($dependencies) {
            $route = $dependencies->routeFactory->createNew();

            return $route;
        });
        $dependencies->textTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            return $resource->getName().'-'.$property.'-'.$locale;
        });

        $dependencies->routeTranslator->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $route): void {
            $this->assertEquals('/harry-hirsch-name-'.$locale, $route->getStaticPrefix());
        });
        $dependencies->routeTranslator->expects($this->exactly(1))->method('setTranslation');

        $entity = new RouteableMock();
        $entity->setRoute($dependencies->routeFactory->createNew());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => false,
            'overwrite' => false,
            'generate_translations' => true,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => false,
        ]);
    }

    public function testGenerateTranslationsPrefix()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) use ($dependencies) {
            $route = $dependencies->routeFactory->createNew();

            return $route;
        });
        $dependencies->textTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            return $resource->getName().'-'.$property.'-'.$locale;
        });

        $dependencies->routeTranslator->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $route): void {
            $this->assertEquals('/'.$locale.'/harry-hirsch-name-'.$locale, $route->getStaticPrefix());
        });
        $dependencies->routeTranslator->expects($this->exactly(1))->method('setTranslation');

        $entity = new RouteableMock();
        $entity->setRoute($dependencies->routeFactory->createNew());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => false,
            'overwrite' => false,
            'generate_translations' => true,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => true,
        ]);
    }

    public function testGenerateTranslationsPrefixedFallback()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru', 'gr']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) use ($dependencies) {
            $route = $dependencies->routeFactory->createNew();
            if ('gr' == $locale) {
                $route->setStaticPrefix('/prefixed');
            }

            return $route;
        });
        $dependencies->textTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            return null;
        });

        $dependencies->routeTranslator->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $route): void {
            $this->assertEquals('/'.$locale.'/harry-hirsch', $route->getStaticPrefix());
        });
        $dependencies->routeTranslator->expects($this->exactly(1))->method('setTranslation');

        $entity = new RouteableMock();
        $entity->setRoute($dependencies->routeFactory->createNew());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => false,
            'overwrite' => false,
            'generate_translations' => true,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => true,
            'allow_fallback' => true,
        ]);
    }

    public function testGenerateTranslationsOverwrite()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru', 'gr']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) use ($dependencies) {
            $route = $dependencies->routeFactory->createNew();
            $route->setStaticPrefix('/prefixed');

            return $route;
        });
        $dependencies->textTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            return null;
        });

        $dependencies->routeTranslator->expects($this->exactly(2))->method('setTranslation');

        $entity = new RouteableMock();
        $entity->setRoute($dependencies->routeFactory->createNew());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'property' => 'name',
            'route_property' => 'route',
            'generate_default' => false,
            'overwrite' => true,
            'generate_translations' => true,
            'default_prefix_locale' => false,
            'translation_prefix_locale' => false,
            'allow_fallback' => true,
        ]);
    }

    public function testGetType()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('locale_prefix', $instance->getType());
    }
}

class LocalePrefixGeneratorTestDependencies
{
    /** @var TranslationManager|MockObject */
    public $translationManager;
    /** @var RouteTranslator|MockObject */
    public $routeTranslator;
    /** @var TextTranslator|MockObject */
    public $textTranslator;
    /** @var RouteFactory|MockObject */
    public $routeFactory;
}
