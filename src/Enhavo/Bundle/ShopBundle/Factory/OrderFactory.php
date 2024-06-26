<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class OrderFactory extends Factory
{
    public function createNewCart(): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->createNew();
        $order->setState(OrderInterface::STATE_CART);
        $order->setCurrencyCode('EUR');
        return $order;
    }
}
