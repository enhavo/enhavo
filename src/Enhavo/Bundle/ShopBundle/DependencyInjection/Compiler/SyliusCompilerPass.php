<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyliusCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteOrderItemQuantityModifier($container);
    }

    protected function overwriteOrderItemQuantityModifier(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.order_item_quantity_modifier');
        $definition->setClass('Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier');
        $definition->addArgument($container->getDefinition('sylius.factory.adjustment'));
    }
}