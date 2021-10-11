<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UserManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases(['enhavo_user.user.manager' => $container->getParameter('enhavo_user.user_manager')]);
    }
}
