<?php

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;

class OrderPromotionCouponProcessor implements OrderProcessorInterface
{
    public function __construct(
        private PromotionEligibilityCheckerInterface $promotionEligibilityChecker,
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        if (!$order instanceof \Enhavo\Bundle\ShopBundle\Model\OrderInterface) {
            return;
        }

        $promotionCoupon = $order->getPromotionCoupon();
        if ($promotionCoupon === null) {
            return;
        }

        foreach ($order->getPromotions() as $promotion) {
            if ($promotion === $promotionCoupon->getPromotion()) {
                return;
            }
        }

        if (!$this->promotionEligibilityChecker->isEligible($order, $promotionCoupon->getPromotion())) {
            $order->setPromotionCoupon(null);
        }
    }
}
