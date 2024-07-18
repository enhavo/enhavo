<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Collection\CollectionFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CollectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $collectionFactory = $container->findDefinition(CollectionFactory::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_resource.collection') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $collectionFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
