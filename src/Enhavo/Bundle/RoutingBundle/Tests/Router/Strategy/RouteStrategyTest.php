<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Strategy;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\Strategy\RouteStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class RouteStrategyTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new RouteStrategyDependencies();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(RouteStrategyDependencies $dependencies)
    {
        $instance = new RouteStrategy($dependencies->router);

        return $instance;
    }

    public function testGenerate()
    {
        $dependencies = $this->createDependencies();

        $route = new Route();
        $route->setStaticPrefix('/servus');
        $route->setName('test');

        $dependencies->router->method('generate')->willReturnCallback(function (string $routeName) use ($route) {
            if ($routeName === $route->getName()) {
                return $route->getStaticPrefix();
            }
            throw new RouteNotFoundException();
        });

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve();

        $path = $instance->generate($route, options: $options);

        $this->assertEquals('/servus', $path);
    }

    public function testGenerateWithEmptyName()
    {
        $dependencies = $this->createDependencies();

        $route = new Route();
        $route->setStaticPrefix('/servus');

        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve();

        $this->expectException(UrlResolverException::class);

        $instance->generate($route, options: $options);
    }
}

class RouteStrategyDependencies
{
    /** @var MockObject|RouterInterface */
    public $router;
}
