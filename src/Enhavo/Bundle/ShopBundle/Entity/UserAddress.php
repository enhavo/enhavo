<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class UserAddress implements AddressSubjectInterface, ResourceInterface
{
    private ?int $id = null;

    private ?AddressInterface $billingAddress = null;

    private ?AddressInterface $shippingAddress = null;

    private ?UserInterface $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBillingAddress(): ?AddressInterface
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?AddressInterface $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?AddressInterface $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
