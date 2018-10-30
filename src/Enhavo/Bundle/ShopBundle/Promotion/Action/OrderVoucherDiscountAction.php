<?php
/**
 * OrderFixedDisountAction.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Entity\Voucher;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class OrderVoucherDiscountAction extends AbstractDiscountAction
{
    use ContainerAwareTrait;

    protected function configureAdjustments(OrderInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        $adjustment = $this->createAdjustment($promotion);
        $adjustment->setType(\Sylius\Component\Core\Model\AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);

        $total = $subject->getTotal();
        $voucher = $this->getVoucher($subject);
        $amount = min($total, $voucher->getAvailableAmount());
        $adjustment->setAmount((- $amount));

        return $adjustment;
    }

    /**
     * @param OrderInterface $subject
     * @return Voucher
     */
    private function getVoucher(OrderInterface $subject)
    {
        $code = $subject->getPromotionCoupon()->getCode();
        $voucher = $this->container->get('enhavo_shop.repository.voucher')->findOneBy([
            'code' => $code
        ]);
        return $voucher;
    }
}