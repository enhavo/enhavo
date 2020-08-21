<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\Type\SlugTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SlugTranslationTypeTest extends TestCase
{

    private function createDependencies()
    {
        /** @var TextTranslator|MockObject $textTranslator */
        $textTranslator = $this->getMockBuilder(TextTranslator::class)->disableOriginalConstructor()->getMock();
        return $textTranslator;
    }

    private function createInstance($textTranslator)
    {
        return new SlugTranslationType();
    }

//    public function testSetTranslation()
//    {
//        $slugTranslator = $this->createDependencies();
//        $slugTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
//            $this->assertTrue(is_object($data));
//            $this->assertEquals('slug', $property);
//            $this->assertEquals('en', $locale);
//            $this->assertEquals('value', $value);
//        });
//
//        $type = $this->createInstance($slugTranslator);
//        $type->setTranslation(['option' => 'value'], new \stdClass(), 'slug', 'en', 'value');
//    }
//
//    public function testGetTranslation()
//    {
//        $slugTranslator = $this->createDependencies();
//        $slugTranslator->expects($this->once())->method('getTranslation')->willReturn('Slug');
//
//        $data = new \stdClass();
//
//        $type = $this->createInstance($slugTranslator);
//        $this->assertEquals('Slug', $type->getTranslation([], $data, 'slug', 'de'));
//    }


    public function testGetName()
    {
        $this->assertEquals('slug', SlugTranslationType::getName());
    }

    public function testGetParentType()
    {

        $this->assertEquals(TextTranslationType::class, SlugTranslationType::getParentType());
    }

}
