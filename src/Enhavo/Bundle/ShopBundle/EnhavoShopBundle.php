<?php

namespace Enhavo\Bundle\ShopBundle;

use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\DocumentGeneratorPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\OrderTaxesApplicatorRegisterPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\PriceCalculatorRegisterPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\ProductVariantProxyEnhancerPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\ShippingCalculatorPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoShopBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigCompilerPass());
        $container->addCompilerPass(new PriceCalculatorRegisterPass());
        $container->addCompilerPass(new OrderTaxesApplicatorRegisterPass());
        $container->addCompilerPass(new ProductVariantProxyEnhancerPass());
        $container->addCompilerPass(new ShippingCalculatorPass());
        $container->addCompilerPass(new DocumentGeneratorPass());
    }
}
