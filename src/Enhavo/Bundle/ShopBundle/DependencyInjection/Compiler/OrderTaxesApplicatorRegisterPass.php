<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class OrderTaxesApplicatorRegisterPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'Enhavo\Bundle\ShopBundle\Manager\TaxationManager',
            'Enhavo\Bundle\ShopBundle\Manager\TaxationManager',
            'enhavo_shop.order_taxes_applicator',
            'addApplicator'
        );
    }
}
