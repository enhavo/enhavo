<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class PriceCalculatorRegisterPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'Enhavo\Bundle\ShopBundle\Manager\PricingManager',
            'Enhavo\Bundle\ShopBundle\Manager\PricingManager',
            'enhavo_shop.price_calculator',
            'addCalculator'
        );
    }
}
