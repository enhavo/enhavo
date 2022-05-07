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
        $this->createProductVariantProxyFactoryInterface($container);
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
