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
        foreach ($container->findTaggedServiceIds('enhavo_api.data_normalizer') as $id => $tagAttributes) {
            $priority = null;
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['priority'])) {
                    $priority = intval($attributes['priority']);
                }
            }
            $services[$id] = new Reference($id);
            $definition = $container->findDefinition($id);
            $arguments = [$definition->getClass()];
            if ($priority !== null) {
                $arguments[] = $priority;
            }
            $dataNormalizer->addMethodCall('register', $arguments);
        }

        $dataNormalizer->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
