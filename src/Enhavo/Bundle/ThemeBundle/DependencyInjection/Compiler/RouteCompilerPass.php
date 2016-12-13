<?php
/**
 * ThemeBundle.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouteCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteRouteType($container);
    }

    protected function overwriteRouteType(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('enhavo_routing');
        $definition->replaceArgument(0, $container->getDefinition('enhavo_theme.url_resolver'));
    }
}