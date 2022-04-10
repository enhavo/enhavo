<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Manager\PricingManager;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

class OrderPricesCalculator implements OrderProcessorInterface
{
    public function __construct(
        private PricingManager $pricingManager
    ) { }

    public function process(BaseOrderInterface $order): void
    {
        foreach ($order->getItems() as $item) {
            if ($item->isImmutable()) {
                continue;
            }

            if ($item instanceof OrderItemInterface) {
                $item->setUnitPrice($this->pricingManager->calculatePrice($item->getProduct(), $item->getConfiguration()));
            }
        }
    }
}
