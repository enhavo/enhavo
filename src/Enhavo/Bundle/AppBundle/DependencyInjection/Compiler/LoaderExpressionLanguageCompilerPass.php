<?php
/**
 * FilesystemCompilerPass.php
 *
 * @since 01/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoaderExpressionLanguageCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $routeCollector = $container->getDefinition('enhavo_app.endpoint.expression_language');

        foreach ($container->findTaggedServiceIds('enhavo_app.endpoint.expression_language') as $id => $attributes) {
            $routeCollector->addMethodCall('registerProvider', [new Reference($id)]);
        }
    }
}
