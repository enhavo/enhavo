<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Doctrine\Persistence\ObjectRepository;
use Sylius\Component\Promotion\Factory\PromotionCouponFactoryInterface;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;

class PromotionCouponFactory implements PromotionCouponFactoryInterface
{
    public function __construct(
        private PromotionCouponFactoryInterface $decoratedFactory,
        private ObjectRepository $promotionRepository,
    ) {}

    public function createNew()
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForPromotion(PromotionInterface $promotion): PromotionCouponInterface
    {
        return $this->decoratedFactory->createForPromotion($promotion);
    }

    public function createForPromotionId($promotionId): PromotionCouponInterface
    {
        $promotion = $this->promotionRepository->find($promotionId);
        if (!$promotion) {
            throw new \Exception('Coupon cannot be created without a Promotion');
        }

        return $this->createForPromotion($promotion);
    }
}
