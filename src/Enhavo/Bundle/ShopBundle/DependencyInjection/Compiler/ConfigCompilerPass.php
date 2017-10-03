<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->createConfirmMailerAlias($container);
        $this->createTrackingMailerAlias($container);
        $this->createNotificationMailerAlias($container);
    }

    public function createConfirmMailerAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_shop.mailer.confirm.service');
        $container->setAlias('enhavo_shop.mailer.confirm_mailer', $providerServiceName);
    }

    public function createTrackingMailerAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_shop.mailer.tracking.service');
        $container->setAlias('enhavo_shop.mailer.tracking_mailer', $providerServiceName);
    }

    public function createNotificationMailerAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_shop.mailer.notification.service');
        $container->setAlias('enhavo_shop.mailer.notification_mailer', $providerServiceName);
    }
}