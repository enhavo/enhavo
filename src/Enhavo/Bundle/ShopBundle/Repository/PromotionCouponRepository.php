<?php

namespace Enhavo\Bundle\ShopBundle\Repository;

class PromotionCouponRepository extends \Sylius\Bundle\PromotionBundle\Doctrine\ORM\PromotionCouponRepository
{
    public function findByPromotionId($promotionId)
    {
        $queryBuilder = $this->createQueryBuilder('promotionCoupon');
        $queryBuilder
            ->andWhere('promotionCoupon.promotion = :promotionId')
            ->setParameter('promotionId', $promotionId);

        return $queryBuilder->getQuery()->getResult();
    }
}
