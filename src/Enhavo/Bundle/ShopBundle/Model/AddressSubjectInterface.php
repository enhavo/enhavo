<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Sylius\Component\Addressing\Model\AddressInterface;

interface AddressSubjectInterface
{
    public function setShippingAddress(?AddressInterface $shippingAddress): void;

    public function getShippingAddress(): ?AddressInterface;

    public function setBillingAddress(?AddressInterface $billingAddress): void;

    public function getBillingAddress(): ?AddressInterface;
}
