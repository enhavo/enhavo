<?php

namespace Enhavo\Bundle\ApiBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\ApiBundle\Normalizer\DataNormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataNormalizerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $dataNormalizer = $container->findDefinition(DataNormalizer::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_api.data_normalizer') as $id => $tag) {
            $services[$id] = new Reference($id);
            $definition = $container->findDefinition($id);
            $dataNormalizer->addMethodCall('register', [$definition->getClass()]);
        }

        $dataNormalizer->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
