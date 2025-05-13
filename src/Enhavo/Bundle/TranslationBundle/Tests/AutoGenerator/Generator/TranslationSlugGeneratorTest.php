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

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator\TranslationSlugGenerator;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationSlugGeneratorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new TranslationSlugGeneratorTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        return new TranslationSlugGenerator(
            $dependencies->translationManager,
            $dependencies->translator,
            $dependencies->em
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
            'slug_property',
            'unique',
        ];
        sort($options);
        sort($assert);

        $this->assertEquals($assert, $options);
    }

    public function testGenerate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translationManager->method('getLocales')->willReturn([
            'pl', 'en', 'fr',
        ]);
        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            if ('slug' === $property) {
                return null;
            }

            return $property.'-'.$locale;
        });
        $dependencies->translator->expects($this->exactly(2))->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $value): void {
            $this->assertEquals('name-'.$locale, $value);
        });
        $instance = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('harry');

        $instance->generate($entity, [
            'property' => 'name',
            'slug_property' => 'slug',
            'unique' => false,
            'overwrite' => false,
        ]);
        $slug = $entity->getSlug();
        $this->assertEquals('harry', $slug);
    }

    public function testGenerateNoOverwrite()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translationManager->method('getLocales')->willReturn([
            'pl', 'en', 'fr',
        ]);
        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            return $property.'-'.$locale;
        });
        $dependencies->translator->expects($this->never())->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $value): void {
            $this->assertEquals('name-'.$locale, $value);
        });
        $instance = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('harry');

        $instance->generate($entity, [
            'property' => 'name',
            'slug_property' => 'slug',
            'unique' => false,
            'overwrite' => false,
        ]);
        $slug = $entity->getSlug();
        $this->assertEquals('harry', $slug);
    }

    public function testGetType()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('translation_slug', $instance->getType());
    }
}

class TranslationSlugGeneratorTestDependencies
{
    /** @var TranslationManager|MockObject */
    public $translationManager;
    /** @var TranslatorInterface|MockObject */
    public $translator;
    /** @var EntityManagerInterface|MockObject */
    public $em;
}
