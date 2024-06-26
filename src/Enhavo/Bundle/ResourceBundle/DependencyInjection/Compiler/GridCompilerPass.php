<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Grid\GridFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GridCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $gridFactory = $container->findDefinition(GridFactory::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_resource.grid') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $gridFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
