<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\RouteTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use PHPUnit\Framework\TestCase;

class RouteTranslationTypeTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('route', RouteTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $routeTranslator = $this->getMockBuilder(RouteTranslator::class)->disableOriginalConstructor()->getMock();
        $routeTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue(is_object($data));
            $this->assertEquals('route', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = new RouteTranslationType($routeTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'route', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();

        $routeTranslator = $this->getMockBuilder(RouteTranslator::class)->disableOriginalConstructor()->getMock();
        $routeTranslator->expects($this->once())->method('getTranslation')->willReturn($route);

        $data = new \stdClass();

        $type = new RouteTranslationType($routeTranslator);
        $this->assertTrue($route === $type->getTranslation([], $data, 'route', 'de'));
    }
}
