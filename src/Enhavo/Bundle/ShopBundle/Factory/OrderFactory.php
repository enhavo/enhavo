<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class OrderFactory extends Factory
{
    public function createNewCart(): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->createNew();
        $order->setState(OrderInterface::STATE_CART);
        $order->setCurrencyCode(OrderInterface::STATE_CART);
        return $order;
    }
}
