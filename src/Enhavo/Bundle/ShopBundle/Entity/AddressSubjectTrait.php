<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Sylius\Component\Addressing\Model\AddressInterface;

trait AddressSubjectTrait
{
    private ?AddressInterface $shippingAddress;
    private ?AddressInterface $billingAddress;
    private ?bool $sameAddress;

    public function setShippingAddress(?AddressInterface $shippingAddress): void
    {
        if ($this->sameAddress) {
            $this->billingAddress = $shippingAddress;
        }
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    public function setBillingAddress(?AddressInterface $billingAddress): void
    {
        if ($this->sameAddress) {
            return;
        }
        $this->billingAddress = $billingAddress;
    }

    public function getBillingAddress(): ?AddressInterface
    {
        return $this->billingAddress;
    }

    public function isSameAddress(): bool
    {
        return (bool)$this->sameAddress;
    }

    public function setSameAddress(?bool $value): void
    {
        $this->sameAddress = (bool)$value;
    }
}
