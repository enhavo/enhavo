<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\RouteTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteTranslationTypeTest extends TestCase
{
    /** @var RouteTranslator|MockObject $routeTranslator */
    private function createDependencies()
    {
        $routeTranslator = $this->getMockBuilder(RouteTranslator::class)->disableOriginalConstructor()->getMock();

        return $routeTranslator;
    }

    private function createInstance($routeTranslator)
    {
        return new RouteTranslationType($routeTranslator);
    }

    public function testGetName()
    {
        $this->assertEquals('route', RouteTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue(is_object($data));
            $this->assertEquals('route', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = $this->createInstance($routeTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'route', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $route = $this->getMockBuilder(RouteInterface::class)->getMock();
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('getTranslation')->willReturn($route);

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);
        $this->assertTrue($route === $type->getTranslation([], $data, 'route', 'de'));
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
        $resolver = new OptionsResolver();

        $type = $this->createInstance($this->createDependencies());

        $type->configureOptions($resolver);

        $this->assertEquals(['allow_null'], $resolver->getDefinedOptions());
    }
}
