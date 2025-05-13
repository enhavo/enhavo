<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

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
        $routeCollector = $container->getDefinition('enhavo_app.route_collector');

        foreach ($container->findTaggedServiceIds('enhavo_app.route_collector') as $id => $attributes) {
            $routeCollector->addMethodCall('addRouteCollector', [new Reference($id)]);
        }
    }
}
