<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Grid;

use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new GridDependencies();
        $dependencies->routeResolver = $this->createMock(RouteResolverInterface::class);
        $dependencies->container = new Container();
        $dependencies->container->set(RouteResolverInterface::class, $dependencies->routeResolver);
        return $dependencies;
    }

    public function createInstance(GridDependencies $dependencies)
    {
        $instance = new Grid(
        );

        $instance->setContainer($dependencies->container);

        return $instance;
    }

    public function testNestedConfigureOptions()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $optionResolver = new OptionsResolver();
        $instance->configureOptions($optionResolver);
        $options = $optionResolver->resolve([
            'resource' => 'app.test'
        ]);

        $this->assertIsArray($options['routes']);
    }
}

class GridDependencies
{
    public ContainerInterface|MockObject $container;
    public RouteResolverInterface|MockObject $routeResolver;
}
