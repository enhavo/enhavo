<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InputCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $gridFactory = $container->findDefinition(InputFactory::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_resource.input') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $gridFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
