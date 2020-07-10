<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use PHPUnit\Framework\TestCase;

class TextTranslationTypeTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('text', TextTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $textTranslator = $this->getMockBuilder(TextTranslator::class)->disableOriginalConstructor()->getMock();
        $textTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue(is_object($data));
            $this->assertEquals('name', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = new TextTranslationType($textTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'name', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $textTranslator = $this->getMockBuilder(TextTranslator::class)->disableOriginalConstructor()->getMock();
        $textTranslator->expects($this->once())->method('getTranslation')->willReturn('Something');

        $type = new TextTranslationType($textTranslator);
        $this->assertEquals('Something', $type->getTranslation([], new \stdClass(), 'text', 'de'));
    }
}
