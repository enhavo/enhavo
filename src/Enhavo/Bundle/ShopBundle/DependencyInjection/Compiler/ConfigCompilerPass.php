<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ShopBundle\Exception\ConfigurationException;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->createConfirmMailerAlias($container);
        $this->createTrackingMailerAlias($container);
        $this->createNotificationMailerAlias($container);
        $this->createProductVariantProxyFactoryInterface($container);
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

    public function createProductVariantProxyFactoryInterface(ContainerBuilder $container)
    {
        $factoryService = $container->getParameter('enhavo_shop.product.variant_proxy.factory');
        if (!$container->hasDefinition($factoryService)) {
            throw ConfigurationException::productVariantProxyFactory($factoryService);
        }
        $container->setAlias(ProductVariantProxyFactoryInterface::class, $factoryService);
    }
}
