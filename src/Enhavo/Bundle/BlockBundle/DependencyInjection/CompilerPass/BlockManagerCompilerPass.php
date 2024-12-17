<?php

namespace Enhavo\Bundle\BlockBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BlockManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $blocks = $container->getParameter('enhavo_block.blocks');
        $services = [];
        foreach ($blocks as $block) {
            $factory = $block['factory'] ?? null;
            if ($factory && $container->hasDefinition($factory)) {
                $services[$factory] = new Reference($factory);
            }
        }

        $factoryServices = $container->findTaggedServiceIds('enhavo_block.factory');
        foreach ($factoryServices as $factoryId => $factoryTags) {
            if (!isset($services[$factoryId])) {
                $services[$factoryId] = new Reference($factoryId);
            }
        }

        $blockManager = $container->findDefinition(BlockManager::class);
        $blockManager->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
