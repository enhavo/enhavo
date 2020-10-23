<?php
/**
 * Created by PhpStorm.
 * User: fliebl
 * Date: 10.09.20
 * Time: 09:40
 */

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases(['enhavo_multi_tenancy.provider' => $container->getParameter('enhavo_multi_tenancy.provider')]);
    }
}
