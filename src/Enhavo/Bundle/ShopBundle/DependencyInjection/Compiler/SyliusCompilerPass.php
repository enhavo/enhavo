<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteOrderItemQuantityModifier($container);
        $this->overwriteProductOption($container);
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
}

