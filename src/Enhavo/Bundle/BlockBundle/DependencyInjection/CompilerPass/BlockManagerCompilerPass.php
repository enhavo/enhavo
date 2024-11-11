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

        $blockManager = $container->findDefinition(BlockManager::class);
        $blockManager->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
