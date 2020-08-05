<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:42
 */

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\MultiTenancyBundle\ExpressionLanguage\TenantExpressionFunctionProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.expression_language');
        $definition->addMethodCall('registerProvider', [
            new Reference(TenantExpressionFunctionProvider::class)
        ]);
    }
}
