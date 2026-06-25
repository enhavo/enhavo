<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Client\TranslationClientInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextTranslationTypeTest extends TestCase
{
    private function createDependencies(): TextTranslationTypeDependencies
    {
        $dependencies = new TextTranslationTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TextTranslator::class)->disableOriginalConstructor()->getMock();
        $dependencies->translationClient = $this->getMockBuilder(TranslationClientInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(TextTranslationTypeDependencies $dependencies)
    {
        return new TextTranslationType(
            $dependencies->translator,
            $dependencies->translationClient,
            $dependencies->defaultLanguage,
        );
    }

    public function testGetName()
    {
        $this->assertEquals('text', TextTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value): void {
            $this->assertTrue(is_object($data));
            $this->assertEquals('name', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = $this->createInstance($dependencies);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'name', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('getTranslation')->willReturn('Something');

        $type = $this->createInstance($dependencies);
        $this->assertEquals('Something', $type->getTranslation([], new \stdClass(), 'text', 'de'));
    }

    public function testGetDefaultValue()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('getDefaultValue')->willReturn('Something');

        $type = $this->createInstance($dependencies);
        $this->assertEquals('Something', $type->getDefaultValue([], new \stdClass(), 'text'));
    }

    public function testTranslate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('translate');

        $data = new \stdClass();

        $type = $this->createInstance($dependencies);

        $type->translate($data, 'field', 'de', []);
    }

    public function testDetach()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('detach');

        $data = new \stdClass();

        $type = $this->createInstance($dependencies);

        $type->detach($data, 'field', 'de', []);
    }

    public function testDelete()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('delete');

        $data = new \stdClass();

        $type = $this->createInstance($dependencies);

        $type->delete($data, 'field');
    }

    public function testConfigureOptions()
    {
        $dependencies = $this->createDependencies();
        $resolver = new OptionsResolver();

        $type = $this->createInstance($dependencies);

        $type->configureOptions($resolver);

        $this->assertEquals([
            'allow_fallback',
            'allow_auto_translate',
            'html',
            'overwrite',
            'context_groups',
        ], $resolver->getDefinedOptions());
    }
}

class TextTranslationTypeDependencies
{
    public TranslatorInterface|MockObject|null $translator = null;
    public TranslationClientInterface|MockObject|null $translationClient = null;
    public ?string $defaultLanguage = null;
}
