<?php

namespace Enhavo\Bundle\ShopBundle\Address;

use Sylius\Component\Addressing\Model\AddressInterface;

interface AddressProviderInterface
{
    public function getBillingAddress(): ?AddressInterface;
    public function getShippingAddress(): ?AddressInterface;
}
