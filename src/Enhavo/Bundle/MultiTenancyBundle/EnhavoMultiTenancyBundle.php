<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle;

use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler\ConditionUrlMatcherCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler\ResolverCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler\RolesProviderCompilerPass;
use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler\TenantResolverAwareCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMultiTenancyBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ProviderCompilerPass());
        $container->addCompilerPass(new ResolverCompilerPass());
        $container->addCompilerPass(new TenantResolverAwareCompilerPass());
        $container->addCompilerPass(new RolesProviderCompilerPass());
        $container->addCompilerPass(new ConditionUrlMatcherCompilerPass());
    }
}
