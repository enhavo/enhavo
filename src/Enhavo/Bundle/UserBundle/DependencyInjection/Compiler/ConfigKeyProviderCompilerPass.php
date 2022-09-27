<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigKeyProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $userManagerService = $container->getParameter('enhavo_user.config_key_provider');
        $container->addAliases(['enhavo_user.config_key_provider' => $userManagerService]);
    }
}
