<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CouponManager
{
    use ContainerAwareTrait;

    public function update(OrderInterface $order)
    {
        if($order->getPromotionCoupon()) {
            $used = $order->getPromotionCoupon()->getUsed();
            $used = intval($used) + 1;
            $order->getPromotionCoupon()->setUsed($used);
            $this->container->get('enhavo_shop.manager.voucher_manager')->update($order);
        }
    }
}