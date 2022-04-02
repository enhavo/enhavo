<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class OrderAdjustmentsClearer implements OrderProcessorInterface
{
    private array $adjustmentsToRemove;

    public function __construct()
    {
        $this->adjustmentsToRemove = [
            AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT,
            AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT,
            AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT,
            AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT,
            AdjustmentInterface::TAX_ADJUSTMENT,
        ];
    }

    public function process(OrderInterface $order): void
    {
        foreach ($this->adjustmentsToRemove as $type) {
            $order->removeAdjustmentsRecursively($type);
        }
    }
}
