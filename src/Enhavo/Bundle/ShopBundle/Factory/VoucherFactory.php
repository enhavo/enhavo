<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 02:39
 */

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ShopBundle\Entity\Voucher;
use Sylius\Component\Promotion\Model\Coupon;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class VoucherFactory extends Factory
{
    use ContainerAwareTrait;

    public function createNew()
    {
        /** @var Voucher $new */
        $new = parent::createNew();
        $new->setCoupon($this->createCoupon());
        $new->setCode($this->generateCode());
        return $new;
    }

    private function generateCode()
    {
        return $this->container->get('enhavo_shop.manager.voucher_manager')->createCode();
    }

    private function createCoupon()
    {
        /** @var Coupon $coupon */
        $coupon = $this->container->get('sylius.factory.promotion_coupon')->createNew();
        $promotion = $this->container->get('sylius.repository.promotion')->findOneBy([
            'code' => 'voucher'
        ]);

        if($promotion === null) {
            throw new \InvalidArgumentException(sprintf('No promotion with code "voucher" exists. Can\' create voucher'));
        }

        $coupon->setPromotion($promotion);
        return $coupon;
    }
}