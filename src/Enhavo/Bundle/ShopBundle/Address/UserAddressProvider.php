<?php

namespace Enhavo\Bundle\ShopBundle\Address;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Entity\UserAddress;
use Enhavo\Bundle\ShopBundle\Factory\AddressFactory;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserAddressProvider implements AddressProviderInterface
{
    private ?UserAddress $cachedUserAddress = null;

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private EntityManagerInterface $em,
        private AddressFactory $addressFactory,
    ) {}

    public function getBillingAddress(): ?AddressInterface
    {
        $userAddress = $this->getUserAddress();
        if ($userAddress) {
            return $this->createNew($userAddress->getBillingAddress());
        }
        return null;
    }

    public function getShippingAddress(): ?AddressInterface
    {
        $userAddress = $this->getUserAddress();
        if ($userAddress) {
            return $this->createNew($userAddress->getShippingAddress());
        }
        return null;
    }

    private function getUserAddress(): ?UserAddress
    {
        if ($this->cachedUserAddress) {
            return $this->cachedUserAddress;
        }

        $user = $this->tokenStorage->getToken()?->getUser();
        if ($user instanceof UserInterface) {
            $userAddress = $this->em->getRepository(UserAddress::class)->findOneBy([
                'user' => $user
            ]);
            $this->cachedUserAddress = $userAddress;
        }

        return $this->cachedUserAddress;
    }

    private function createNew(AddressInterface $address)
    {
        return $this->addressFactory->createFromAddress($address);
    }
}
