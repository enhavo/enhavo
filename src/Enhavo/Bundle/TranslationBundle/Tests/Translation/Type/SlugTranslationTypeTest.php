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

    public function testGetName()
    {
        $this->assertEquals('slug', SlugTranslationType::getName());
    }

    public function testGetParentType()
    {

        $this->assertEquals(TextTranslationType::class, SlugTranslationType::getParentType());
    }

}
