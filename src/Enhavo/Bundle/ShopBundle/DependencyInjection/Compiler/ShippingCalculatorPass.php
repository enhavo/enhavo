<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ShopBundle\Shipping\DelegatingCalculator as EnhavoDelegatingCalculator;
use Sylius\Component\Shipping\Calculator\DelegatingCalculator as SyliusDelegatingCalculator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ShippingCalculatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->removeDefinition('sylius.shipping_calculator');
        $container->addAliases([
            'sylius.shipping_calculator' => EnhavoDelegatingCalculator::class,
            SyliusDelegatingCalculator::class => EnhavoDelegatingCalculator::class,
        ]);
    }
}
