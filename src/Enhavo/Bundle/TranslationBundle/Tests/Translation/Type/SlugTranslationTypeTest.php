<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\RoutingBundle\Model\SlugInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\SlugTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Slug\SlugTranslator;
use PHPUnit\Framework\TestCase;

class SlugTranslationTypeTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('slug', SlugTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $slugTranslator = $this->getMockBuilder(SlugTranslator::class)->disableOriginalConstructor()->getMock();
        $slugTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue(is_object($data));
            $this->assertEquals('slug', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = new SlugTranslationType($slugTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'slug', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $slugTranslator = $this->getMockBuilder(SlugTranslator::class)->disableOriginalConstructor()->getMock();
        $slugTranslator->expects($this->once())->method('getTranslation')->willReturn('Slug');

        $data = new \stdClass();

        $type = new SlugTranslationType($slugTranslator);
        $this->assertEquals('Slug', $type->getTranslation([], $data, 'slug', 'de'));
    }
}
