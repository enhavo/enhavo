<?php

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MultiTenancyBundle\Security\Roles\TenancyRolesProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RolesProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('security.roles.provider');
        $definition->addMethodCall('addProvider', [
            new Reference(TenancyRolesProvider::class)
        ]);
    }
}
