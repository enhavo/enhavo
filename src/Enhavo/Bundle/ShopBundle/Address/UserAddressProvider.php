<?php

namespace Enhavo\Bundle\ShopBundle\Address;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Entity\UserAddress;
use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserAddressProvider implements AddressProviderInterface
{
    private ?UserAddress $cachedUserAddress = null;

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private EntityManagerInterface $em,
        private FactoryInterface $userAddressFactory,
    ) {}

    public function getAddress(): AddressSubjectInterface
    {
        if ($this->cachedUserAddress) {
            return $this->cachedUserAddress;
        }

        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof UserInterface) {
            throw new \Exception('User is not authenticated');
        }

        $userAddress = $this->em->getRepository(UserAddress::class)->findOneBy([
            'user' => $user
        ]);

        if ($userAddress === null) {
            $userAddress = $this->userAddressFactory->createNew();
            $this->em->persist($userAddress);
            $userAddress->setUser($user);
        }

        $this->cachedUserAddress = $userAddress;

        return $this->cachedUserAddress;
    }
}
