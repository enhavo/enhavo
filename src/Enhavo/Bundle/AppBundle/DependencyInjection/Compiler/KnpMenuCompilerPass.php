<?php
/**
 * KnpMenuCompilerPass.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpMenuCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('knp_menu.factory');
        $definition->setClass('Enhavo\Bundle\AppBundle\Menu\MenuFactory');
    }
}