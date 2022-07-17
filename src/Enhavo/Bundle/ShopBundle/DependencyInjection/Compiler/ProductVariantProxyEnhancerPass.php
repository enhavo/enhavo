<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ShopBundle\Product\ProductVariantProxyEnhancerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ProductVariantProxyEnhancerPass implements CompilerPassInterface
{
    const TAG = 'enhavo_shop.product_variant_proxy_enhancer';

    public function process(ContainerBuilder $container)
    {
        $factoryService = $container->getDefinition($container->getParameter('enhavo_shop.product.variant_proxy.factory'));
        $definitions = $container->findTaggedServiceIds(self::TAG);

        foreach ($definitions as $id => $values) {
            $priority = 10;
            foreach ($values as $valueItem) {
                if (isset($valueItem['priority'])) {
                    $priority = $valueItem['priority'];
                    break;
                }
            }

            $definition = $container->getDefinition($id);
            $factoryService->addMethodCall('addVariantProxyEnhancer', [$definition, $priority]);
        }
    }
}
