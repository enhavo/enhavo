<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 18.11.17
 * Time: 18:35
 */

namespace Enhavo\Bundle\MultiTenancyBundle;

use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass\ProviderCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass\ResolverCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass\SyliusCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass\TenantResolverAwareCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMultiTenancyBundle extends Bundle
{
    public function  build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SyliusCompilerPass());
        $container->addCompilerPass(new ProviderCompilerPass());
        $container->addCompilerPass(new ResolverCompilerPass());
        $container->addCompilerPass(new TenantResolverAwareCompilerPass());
    }
}
