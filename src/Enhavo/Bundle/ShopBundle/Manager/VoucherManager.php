<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\ShopBundle\Entity\Promotion;
use Enhavo\Bundle\ShopBundle\Entity\Voucher;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class VoucherManager
{
    use ContainerAwareTrait;

    public function update(OrderInterface $order)
    {
        if($order->getPromotionCoupon()) {
            $code = $order->getPromotionCoupon()->getCode();
            /** @var Voucher $voucher */
            $voucher = $this->container->get('enhavo_shop.repository.voucher')->findOneBy([
                'code' => $code
            ]);

            if($voucher) {
                $amount = $this->getRedeemAmount($order);
                $voucher->addRedeemAmount($amount);
                $voucher->setRedeemedAt(new \DateTime());

                if($voucher->getAvailableAmount() < 0) {
                    throw new \InvalidArgumentException('The voucher has no more available amount left');
                }
            }
        }
    }

    private function getRedeemAmount(OrderInterface $order)
    {
        $amount = 0;
        $promotion = $this->getPromotion();
        if(!$promotion) {
            return $amount;
        }

        $adjustments = $order->getAdjustments(\Sylius\Component\Core\Model\AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        /** @var AdjustmentInterface $adjustment */
        foreach($adjustments as $adjustment) {
            if($adjustment->getOriginType() == Promotion::class && $adjustment->getOriginId($promotion->getId())) {
                return abs($adjustment->getAmount());
            }
        }
        return $amount;
    }

    /**
     * @return Promotion
     */
    private function getPromotion()
    {
        $promotion = $this->container->get('sylius.repository.promotion')->findOneBy([
            'code' => 'voucher'
        ]);
        return $promotion;
    }

    public function createCode()
    {
        $code = '';
        $characters = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[mt_rand(0, count($characters) - 1)];
        }

        if($this->container->get('enhavo_shop.repository.voucher')->findOneBy(['code' => $code])) {
            return $this->createCode();
        }

        return $code;
    }
}