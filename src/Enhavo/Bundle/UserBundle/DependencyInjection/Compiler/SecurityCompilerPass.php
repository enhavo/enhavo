<?php
/**
 * SecurityCompilerPass.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SecurityCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteAuthenticationSuccessHandler($container);
    }

    protected function overwriteAuthenticationSuccessHandler(ContainerBuilder $container)
    {
//        $definition = $container->getDefinition('security.authentication.success_handler');
//        $definition->setClass('Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationSuccessHandler');
//        $definition->addMethodCall('setContainer', array(new Reference('service_container')));
    }
}
