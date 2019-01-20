<?php
/**
 * OrderFixedDisountAction.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;

class OrderPercentageDiscountAction extends AbstractDiscountAction
{
    protected function configureAdjustments(OrderInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        return [
            $this->createUnitAdjustment($subject, $configuration, $promotion),
            $this->createTaxAdjustment($subject, $configuration, $promotion)
        ];
    }

    protected function createUnitAdjustment(OrderInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        $adjustment = $this->createAdjustment($promotion);
        $adjustment->setType(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);

        $percent = $configuration['percentage'] / 100;

        $total = $subject->getUnitPriceTotal();
        $discount = round($percent*$total);
        $discount = intval($discount);
        $adjustment->setAmount((- $discount));

        return $adjustment;
    }

    protected  function createTaxAdjustment(OrderInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        $adjustment = $this->createAdjustment($promotion);
        $adjustment->setType(AdjustmentInterface::TAX_PROMOTION_ADJUSTMENT);

        $percent = $configuration['percentage'] / 100;

        $total = $subject->getUnitTaxTotal();
        $discount = round($percent*$total);
        $discount = intval($discount);
        $adjustment->setAmount((- $discount));

        return $adjustment;
    }

    public function getType(): ?string
    {
        // TODO: Implement getType() method.
    }

    public function getConfiguration(): array
    {
        // TODO: Implement getConfiguration() method.
    }

    public function getPromotion(): ?PromotionInterface
    {
        // TODO: Implement getPromotion() method.
    }

    public function setType(?string $type): void
    {
        // TODO: Implement setType() method.
    }

    public function setConfiguration(array $configuration): void
    {
        // TODO: Implement setConfiguration() method.
    }

    public function setPromotion(?PromotionInterface $promotion): void
    {
        // TODO: Implement setPromotion() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }
}
