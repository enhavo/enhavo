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

class FilesystemCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('filesystem');
        $definition->setClass('Enhavo\Bundle\AppBundle\Filesystem\Filesystem');
    }
}