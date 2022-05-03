<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Addressing\Model\AddressInterface;

trait AddressSubjectTrait
{
    private ?AddressInterface $shippingAddress = null;
    private ?AddressInterface $billingAddress = null;

    public function setShippingAddress(?AddressInterface $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    public function setBillingAddress(?AddressInterface $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getBillingAddress(): ?AddressInterface
    {
        return $this->billingAddress;
    }
}
