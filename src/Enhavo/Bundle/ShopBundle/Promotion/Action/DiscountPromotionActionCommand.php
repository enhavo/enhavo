<?php

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemUnitInterface;
use Sylius\Component\Promotion\Action\PromotionActionCommandInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

abstract class DiscountPromotionActionCommand implements PromotionActionCommandInterface
{
    /**
     * @throws \InvalidArgumentException
     */
    abstract protected function isConfigurationValid(array $configuration): void;

    public function revert(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): void
    {
        /** @var OrderInterface $subject */
        Assert::isInstanceOf($subject, OrderInterface::class);

        if (!$this->isSubjectValid($subject)) {
            return;
        }

        foreach ($subject->getItems() as $item) {
            foreach ($item->getUnits() as $unit) {
                $this->removeUnitOrderPromotionAdjustmentsByOrigin($unit, $promotion);
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function isSubjectValid(PromotionSubjectInterface $subject): bool
    {
        /** @var OrderInterface $subject */
        Assert::isInstanceOf($subject, OrderInterface::class);

        return 0 !== $subject->countItems();
    }

    private function removeUnitOrderPromotionAdjustmentsByOrigin(
        OrderItemUnitInterface $unit,
        PromotionInterface $promotion
    ): void {
        foreach ($unit->getAdjustments(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT) as $adjustment) {
            if ($promotion->getCode() === $adjustment->getOriginCode()) {
                $unit->removeAdjustment($adjustment);
            }
        }
    }
}
