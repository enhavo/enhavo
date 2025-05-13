<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VoterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $managerService = $container->getDefinition(NavigationManager::class);
        $taggedServices = $container->findTaggedServiceIds('enhavo_navigation.voter');
        foreach ($taggedServices as $id => $tagAttributes) {
            $tagServiceDefinition = $container->getDefinition($id);
            $managerService->addMethodCall(
                'addVoter',
                [$tagServiceDefinition]
            );
        }
    }
}
