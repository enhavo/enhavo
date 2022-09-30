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
        $this->createProductVariantProxyAliases($container);
    }

    public function createProductVariantProxyAliases(ContainerBuilder $container)
    {
        $proxyFactoryId = $container->getParameter('enhavo_shop.product.variant_proxy.factory');

        if (!$container->hasDefinition($proxyFactoryId)) {
            throw ConfigurationException::productVariantProxyFactory($proxyFactoryId);
        }

        $container->setAlias('enhavo_shop.product.variant_proxy.factory', $proxyFactoryId);
        $container->setAlias(ProductVariantProxyFactoryInterface::class, $proxyFactoryId);
    }
}
