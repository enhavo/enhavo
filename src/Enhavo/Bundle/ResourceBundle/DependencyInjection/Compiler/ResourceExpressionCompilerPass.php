<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ResourceExpressionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $resourceExpressionLanguage = $container->findDefinition(ResourceExpressionLanguage::class);

        foreach ($container->findTaggedServiceIds('enhavo_resource.expression_language_function_provider') as $id => $tag) {
            $resourceExpressionLanguage->addMethodCall('addFunctionProvider', [new Reference($id)]);
        }

        foreach ($container->findTaggedServiceIds('enhavo_resource.expression_language_variable_provider') as $id => $tag) {
            $resourceExpressionLanguage->addMethodCall('addVariableProvider', [new Reference($id)]);
        }
    }
}
