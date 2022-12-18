<?php

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Distributor\ProportionalIntegerDistributor;
use Enhavo\Bundle\ShopBundle\Promotion\Applicator\UnitsPromotionAdjustmentsApplicator;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Core\Promotion\Applicator\UnitsPromotionAdjustmentsApplicatorInterface;
use Sylius\Component\Promotion\Action\PromotionActionCommandInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

class PercentageDiscountPromotionActionCommand extends DiscountPromotionActionCommand implements PromotionActionCommandInterface
{
    public const TYPE = 'order_percentage_discount';

    public function __construct(
        private ProportionalIntegerDistributor $distributor,
        private UnitsPromotionAdjustmentsApplicator $unitsPromotionAdjustmentsApplicator
    ) {
    }

    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
    {
        /** @var OrderInterface $subject */
        Assert::isInstanceOf($subject, OrderInterface::class);

        if (!$this->isSubjectValid($subject)) {
            return false;
        }

        try {
            $this->isConfigurationValid($configuration);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }

        $promotionAmount = $this->calculateAdjustmentAmount($subject->getPromotionSubjectTotal(), $configuration['percentage']);
        if (0 === $promotionAmount) {
            return false;
        }

        $itemsTotal = [];
        foreach ($subject->getItems() as $orderItem) {
            $itemsTotal[] = $orderItem->getTotal();
        }

        $splitPromotion = $this->distributor->distribute($itemsTotal, $promotionAmount);
        $this->unitsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion);

        return true;
    }

    protected function isConfigurationValid(array $configuration): void
    {
        Assert::keyExists($configuration, 'percentage');
        Assert::greaterThan($configuration['percentage'], 0);
        Assert::lessThanEq($configuration['percentage'], 1);
    }

    private function calculateAdjustmentAmount(int $promotionSubjectTotal, float $percentage): int
    {
        return -1 * (int) round($promotionSubjectTotal * $percentage);
    }
}
