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

use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextTranslationTypeTest extends TestCase
{
    private function createDependencies()
    {
        /** @var TextTranslator|MockObject $textTranslator */
        $textTranslator = $this->getMockBuilder(TextTranslator::class)->disableOriginalConstructor()->getMock();

        return $textTranslator;
    }

    private function createInstance($textTranslator)
    {
        return new TextTranslationType($textTranslator);
    }

    public function testGetName()
    {
        $this->assertEquals('text', TextTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $textTranslator = $this->createDependencies();
        $textTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value): void {
            $this->assertTrue(is_object($data));
            $this->assertEquals('name', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = $this->createInstance($textTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'name', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $textTranslator = $this->createDependencies();
        $textTranslator->expects($this->once())->method('getTranslation')->willReturn('Something');

        $type = $this->createInstance($textTranslator);
        $this->assertEquals('Something', $type->getTranslation([], new \stdClass(), 'text', 'de'));
    }

    public function testGetDefaultValue()
    {
        $textTranslator = $this->createDependencies();
        $textTranslator->expects($this->once())->method('getDefaultValue')->willReturn('Something');

        $type = $this->createInstance($textTranslator);
        $this->assertEquals('Something', $type->getDefaultValue([], new \stdClass(), 'text'));
    }

    public function testTranslate()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('translate');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->translate($data, 'field', 'de', []);
    }

    public function testDetach()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('detach');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->detach($data, 'field', 'de', []);
    }

    public function testDelete()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('delete');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->delete($data, 'field');
    }

    public function testConfigureOptions()
    {
        $textTranslator = $this->createDependencies();
        $resolver = new OptionsResolver();

        $type = $this->createInstance($textTranslator);

        $type->configureOptions($resolver);

        $this->assertEquals(['allow_fallback'], $resolver->getDefinedOptions());
    }
}
