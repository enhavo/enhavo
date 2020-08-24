<?php


namespace AutoGenerator\Generator;


use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator\LocalePrefixGenerator;
use Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator\TranslationRouteNameGenerator;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\RouteableMock;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationRouteNameGeneratorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new TranslationRouteNameGeneratorTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->routeTranslator = $this->getMockBuilder(RouteTranslator::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        return new TranslationRouteNameGenerator(
            $dependencies->translationManager,
            $dependencies->routeTranslator
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
            'overwrite',
            'route_property'
        ];
        sort($options);
        sort($assert);

        $this->assertEquals($assert, $options);
    }

    public function testGenerateTranslationRouteNames()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru', 'gr']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function ($resource, $property, $locale) {
            $route = $this->getMockBuilder(RouteInterface::class)->getMock();
            if ($locale == 'ru') {
                $route->expects($this->once())->method('getName');
                $route->expects($this->once())->method('setName');
            } else {
                $route->method('getName')->willReturn($locale);
                $route->expects($this->once())->method('getName');
                $route->expects($this->never())->method('setName');
            }

            return $route;
        });

        $entity = new RouteableMock();
        $entity->setRoute(new Route());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'route_property' => 'route',
            'overwrite' => false
        ]);

    }


    public function testGenerateTranslationRouteNamesOverwrite()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->translationManager->method('getDefaultLocale')->willReturn('pl');
        $dependencies->translationManager->method('getLocales')->willReturn(['pl', 'ru', 'gr']);
        $dependencies->routeTranslator->method('getTranslation')->willReturnCallback(function () {
            $route = $this->getMockBuilder(RouteInterface::class)->getMock();
            $route->method('getName')->willReturn('name');
            $route->expects($this->never())->method('getName');
            $route->expects($this->once())->method('setName');

            return $route;
        });

        $entity = new RouteableMock();
        $entity->setRoute(new Route());
        $entity->setName('harry hirsch');

        $instance->generate($entity, [
            'route_property' => 'route',
            'overwrite' => true
        ]);

    }

    public function testGetType()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('translation_route_name', $instance->getType());
    }
}


class TranslationRouteNameGeneratorTestDependencies
{
    /** @var TranslationManager|MockObject */
    public $translationManager;
    /** @var RouteTranslator|MockObject */
    public $routeTranslator;
}
