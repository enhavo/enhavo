<?php

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Distributor\ProportionalIntegerDistributor;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Promotion\Applicator\UnitsPromotionAdjustmentsApplicator;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

class FixedDiscountPromotionActionCommand extends DiscountPromotionActionCommand
{
    public const TYPE = 'order_fixed_discount';

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

        $promotionAmount = $this->calculateAdjustmentAmount(
            $subject->getPromotionSubjectTotal(),
            $configuration['amount']
        );

        if (0 === $promotionAmount) {
            return false;
        }

        $itemsTotals = [];
        foreach ($subject->getItems() as $item) {
            $itemsTotals[] = $item->getTotal();
        }

        $splitPromotion = $this->distributor->distribute($itemsTotals, $promotionAmount);
        $this->unitsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion);

        return true;
    }

    protected function isConfigurationValid(array $configuration): void
    {
        Assert::keyExists($configuration, 'amount');
        Assert::integer($configuration['amount']);
    }

    private function calculateAdjustmentAmount(int $promotionSubjectTotal, int $targetPromotionAmount): int
    {
        return -1 * min($promotionSubjectTotal, $targetPromotionAmount);
    }
}
