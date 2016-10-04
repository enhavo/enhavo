<?php
/**
 * CollectionRepositoryCompiler.php
 *
 * @since 30/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CollectionRepositoryCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('enhavo_category.repository.collection')) {
            return;
        }

        $definition = $container->getDefinition('enhavo_category.repository.collection');
        $definition->addMethodCall('setFactory', [
            new Reference('enhavo_category.factory.collection')
        ]);
    }
}