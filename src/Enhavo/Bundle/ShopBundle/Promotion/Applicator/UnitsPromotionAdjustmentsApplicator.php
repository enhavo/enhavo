<?php

namespace Enhavo\Bundle\ShopBundle\Promotion\Applicator;

use Enhavo\Bundle\ShopBundle\Distributor\IntegerDistributor;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Webmozart\Assert\Assert;

class UnitsPromotionAdjustmentsApplicator
{
    public function __construct(
        private AdjustmentFactoryInterface $adjustmentFactory,
        private IntegerDistributor $distributor
    ) {
    }

    /**
     * @throws UnsupportedTypeException
     */
    public function apply(OrderInterface $order, PromotionInterface $promotion, array $adjustmentsAmounts): void
    {
        Assert::eq($order->countItems(), count($adjustmentsAmounts));

        $i = 0;
        foreach ($order->getItems() as $item) {
            $adjustmentAmount = $adjustmentsAmounts[$i++];
            if (0 === $adjustmentAmount) {
                continue;
            }

            $this->applyAdjustmentsOnItemUnits($item, $promotion, $adjustmentAmount);
        }
    }

    private function applyAdjustmentsOnItemUnits(OrderItemInterface $item, PromotionInterface $promotion, int $itemPromotionAmount): void
    {
        $splitPromotionAmount = $this->distributor->distribute($itemPromotionAmount, $item->getQuantity());

        $i = 0;
        foreach ($item->getUnits() as $unit) {
            $promotionAmount = $splitPromotionAmount[$i++];
            if (0 === $promotionAmount) {
                continue;
            }

            $this->addAdjustment($promotion, $unit, $promotionAmount);
        }
    }

    private function addAdjustment(PromotionInterface $promotion, OrderItemUnitInterface $unit, int $amount): void
    {
        $adjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, $promotion->getName(), $amount)
        ;
        $adjustment->setOriginCode($promotion->getCode());

        $unit->addAdjustment($adjustment);
    }
}
