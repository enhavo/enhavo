<?php

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases([
            'enhavo_multi_tenancy.resolver' => $container->getParameter('enhavo_multi_tenancy.resolver'),
            ResolverInterface::class => $container->getParameter('enhavo_multi_tenancy.resolver')
        ]);
    }
}
