<?php
/**
 * FOSUserCompilerPass.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FOSUserCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteUserManager($container);
        $this->overwriteMailer($container);
    }

    protected function overwriteUserManager(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_user.user_manager.default');
        $definition->setClass('Enhavo\Bundle\UserBundle\User\UserManager');
        $definition->addMethodCall('setContainer', array(new Reference('service_container')));
    }

    protected function overwriteMailer(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_user.mailer.default');
        $definition->setClass('Enhavo\Bundle\UserBundle\User\UserMailer');
    }
}