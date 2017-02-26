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

class OrderFixedDiscountAction extends AbstractDiscountAction
{
    protected function configureAdjustments(OrderInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        $adjustment = $this->createAdjustment($promotion);
        $adjustment->setType(\Sylius\Component\Core\Model\AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        $adjustment->setAmount((- $configuration['amount']));

        return $adjustment;
    }
}