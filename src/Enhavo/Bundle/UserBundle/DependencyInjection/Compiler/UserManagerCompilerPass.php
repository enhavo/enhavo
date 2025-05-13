<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UserManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $userManagerService = $container->getParameter('enhavo_user.user_manager');
        $container->addAliases(['enhavo_user.user.manager' => $userManagerService]);

        $alias = $container->getAlias('enhavo_user.user.manager');
        $alias->setPublic(true);
    }
}
