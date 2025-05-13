<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class BlockManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $blocks = $container->getParameter('enhavo_block.blocks');
        $services = [];
        foreach ($blocks as $key => $block) {
            $factory = $block['factory'] ?? null;
            if ($factory && $container->hasDefinition($factory)) {
                $services[$factory] = new Reference($factory);
            } elseif ($factory) {
                $definition = new Definition($factory);
                $name = sprintf('block.%s.factory', $key);
                $container->setDefinition($name, $definition);
                $services[$name] = new Reference($name);
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
