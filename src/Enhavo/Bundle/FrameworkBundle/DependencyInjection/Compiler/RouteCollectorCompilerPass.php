<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author gseidel
 */
class RouteCollectorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $routeCollector = $container->getDefinition('enhavo_framework.route_collector');

        foreach ($container->findTaggedServiceIds('enhavo_framework.route_collector') as $id => $attributes) {
            $routeCollector->addMethodCall('addRouteCollector', [new Reference($id)]);
        }
    }
}
