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

use Enhavo\Bundle\FrameworkBundle\Init\InitManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author gseidel
 */
class InitCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $gridFactory = $container->findDefinition(InitManager::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_framework.init') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $gridFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
