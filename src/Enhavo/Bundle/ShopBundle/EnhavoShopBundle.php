<?php

namespace Enhavo\Bundle\ShopBundle;

use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\OrderTaxesApplicatorRegisterPass;
use Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler\PriceCalculatorRegisterPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoShopBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ConfigCompilerPass());
        $container->addCompilerPass(new PriceCalculatorRegisterPass());
        $container->addCompilerPass(new OrderTaxesApplicatorRegisterPass());
    }
}
