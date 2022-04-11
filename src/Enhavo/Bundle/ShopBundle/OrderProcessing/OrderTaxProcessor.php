<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Manager\TaxationManager;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Order\Model\OrderInterface as SyliusOrderInterface;

class OrderTaxProcessor implements OrderProcessorInterface
{
    public function __construct(
        private TaxationManager  $taxManager,
    ) {}

    public function process(SyliusOrderInterface $order): void
    {
        $this->taxManager->applyTaxes($order);
    }
}
