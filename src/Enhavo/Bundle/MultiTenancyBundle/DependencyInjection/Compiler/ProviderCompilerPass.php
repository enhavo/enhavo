<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases([
            'enhavo_multi_tenancy.provider' => $container->getParameter('enhavo_multi_tenancy.provider'),
            ProviderInterface::class => $container->getParameter('enhavo_multi_tenancy.provider'),
        ]);
    }
}
