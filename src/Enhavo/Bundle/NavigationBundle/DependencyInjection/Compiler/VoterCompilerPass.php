<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 13:16
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
                array($tagServiceDefinition)
            );
        }
    }
}
