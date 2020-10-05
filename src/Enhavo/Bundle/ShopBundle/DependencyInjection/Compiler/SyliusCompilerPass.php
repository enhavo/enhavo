<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteOrderItemQuantityModifier($container);
        $this->overwriteProductOption($container);
        $this->overwriteProductVariantFactory($container);
    }

    protected function overwriteOrderItemQuantityModifier(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.order_item_quantity_modifier');
        $definition->setClass('Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier');
        $definition->addArgument($container->getDefinition('sylius.factory.adjustment'));
    }

    protected function overwriteProductOption(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.controller.product_option');
        $definition->setClass('Enhavo\Bundle\AppBundle\Controller\ResourceController');
    }

    protected function overwriteProductVariantFactory(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.custom_factory.product_variant');
        $definition->setClass('Enhavo\Bundle\ShopBundle\Factory\ProductVariantFactory');
        $definition->addArgument(new Reference('request_stack'));
        $definition->addArgument(new Reference('sylius.repository.product'));
        $definition->addArgument( ProductVariant::class);
    }
}

