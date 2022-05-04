<?php

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\ShopBundle\Address\AddressProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class OrderManager
{
    public function __construct(
        private AddressProviderInterface $addressProvider
    )
    {}

    public function assignAddress(OrderInterface $order)
    {
        if ($order->getBillingAddress() === null) {
            $order->setBillingAddress($this->addressProvider->getBillingAddress());
        }

        if ($order->getShippingAddress() === null) {
            $order->setShippingAddress($this->addressProvider->getShippingAddress());
        }
    }
}
