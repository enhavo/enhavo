<?php
/**
 * RequestConfigurationCompilerPass.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Viewer\PreviewViewHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FOSRestCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->addPreviewViewHandler($container);
    }

    protected function addPreviewViewHandler(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_rest.view_handler.default');
        $definition->addMethodCall('registerHandler', [
            'preview',
            [new Reference(PreviewViewHandler::class), 'createResponse']
        ]);
    }
}