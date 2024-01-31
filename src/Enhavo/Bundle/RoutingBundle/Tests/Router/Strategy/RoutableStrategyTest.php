<?php

namespace Router\Strategy;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Enhavo\Bundle\RoutingBundle\Router\Strategy\RoutableStrategy;
use Enhavo\Bundle\RoutingBundle\Tests\Mock\RouteContentMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


class RoutableStrategyTest extends TestCase
{

    private function createDependencies()
    {
        $dependencies = new RoutableStrategyDependencies();
        $dependencies->router = $this->getMockBuilder(Router::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(RoutableStrategyDependencies $dependencies)
    {
        $instance = new RoutableStrategy();
        $instance->setRouter($dependencies->router);
        return $instance;
    }

    public function testGenerateRoutable()
    {
        $dependencies = $this->createDependencies();

        $dependencies->router->method('generate')->willReturnCallback(function(Route $route) {
             return $route->getStaticPrefix();
        });

        $mock = new RouteContentMock();
        $route = new Route();
        $mock->setRoute($route);

        $route->setStaticPrefix('/servus');

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve();

        $path = $instance->generate($mock, options: $options);

        $this->assertEquals('/servus', $path);
    }

    public function testGenerateWithEmptyRoute()
    {
        $dependencies = $this->createDependencies();

        $mock = new RouteContentMock();

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve();

        $path = $instance->generate($mock, options: $options);

        $this->assertNull($path);
    }

    public function testGenerateRoutableWithError()
    {
        $dependencies = $this->createDependencies();

        $dependencies->router->method('generate')->willReturnCallback(function(Route $route) {
            throw new RouteNotFoundException();
        });

        $mock = new RouteContentMock();
        $route = new Route();
        $mock->setRoute($route);
        $route->setStaticPrefix('/servus');

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve();


        $this->expectException(UrlResolverException::class);
        $instance->generate($mock, options: $options);
    }

    public function testGenerateRoutableWithNoError()
    {
        $dependencies = $this->createDependencies();

        $dependencies->router->method('generate')->willReturnCallback(function(Route $route) {
            throw new RouteNotFoundException();
        });

        $mock = new RouteContentMock();
        $route = new Route();
        $mock->setRoute($route);
        $route->setStaticPrefix('/servus');

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve([
            'error' => false
        ]);

        $path = $instance->generate($mock, options: $options);

        $this->assertNull($path);
    }
}

class RoutableStrategyDependencies
{
    /** @var MockObject|Router */
    public $router;
}
