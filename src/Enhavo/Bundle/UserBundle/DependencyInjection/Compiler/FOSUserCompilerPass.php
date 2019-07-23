<?php
/**
 * FOSUserCompilerPass.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\UserBundle\User\UserMailer;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Parameter;
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
        $definition->setClass(UserManager::class);
        $definition->addArgument(new Reference('fos_user.util.token_generator'));
        $definition->addArgument(new Reference('fos_user.mailer'));
        $definition->addArgument(new Reference('fos_user.security.login_manager'));
        $definition->addArgument(new Parameter('fos_user.firewall_name'));
    }

    protected function overwriteMailer(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_user.mailer.default');
        $definition->setClass(UserMailer::class);
        $definition->addMethodCall('setContainer', [new Reference('service_container')]);
    }
}
