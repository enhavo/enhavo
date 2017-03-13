<?php
/**
 * OrderAddressProvider.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Entity\UserAddress;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class OrderAddressProvider
{
    /**
     * @var FactoryInterface
     */
    private $addressFactory;

    /**
     * @var OrderProvider
     */
    private $orderProvider;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(FactoryInterface $addressFactory, OrderProvider $orderProvider, EntityManagerInterface $em)
    {
        $this->addressFactory = $addressFactory;
        $this->orderProvider = $orderProvider;
        $this->em = $em;
    }

    public function provide(OrderInterface $order)
    {
        $user = $order->getUser();
        $address = $order->getShippingAddress();

        if ($address === null) {
            $order->setShippingAddress($this->addressFactory->createNew());
            $order->setBillingAddress($this->addressFactory->createNew());

            if ($user instanceof UserInterface) {
                $userAddress = $this->getUserAddress($user);
                if($userAddress) {
                    $this->setAddress($order->getBillingAddress(), $userAddress->getBillingAddress());
                    $this->setAddress($order->getShippingAddress(), $userAddress->getShippingAddress());
                    $order->setDifferentBillingAddress($userAddress->isDifferentBillingAddress());
                } else {
                    $lastOrder = $this->orderProvider->getLastOrder($user);
                    if($lastOrder !== null) {
                        $lastOrderAddress = $this->orderProvider->getLastOrder($user)->getShippingAddress();
                        if($lastOrderAddress) {
                            $this->setAddress($order->getShippingAddress(), $lastOrderAddress);
                        }
                    }
                }
            }
        }
    }

    public function save(OrderInterface $order)
    {
        $user = $order->getUser();
        if($user) {
            $userAddress = $this->getUserAddress($user);
            if($userAddress === null) {
                $userAddress = new UserAddress();
                $userAddress->setBillingAddress($this->addressFactory->createNew());
                $userAddress->setShippingAddress($this->addressFactory->createNew());
                $this->setAddress($userAddress->getBillingAddress(), $order->getBillingAddress());
                $this->setAddress($userAddress->getShippingAddress(), $order->getShippingAddress());

                $userAddress->setDifferentBillingAddress($order->getDifferentBillingAddress());

                $userAddress->setUser($user);
                $this->em->persist($userAddress);
                $this->em->flush();
            }
        }
    }

    private function getUserAddress(UserInterface $user)
    {
        return $this->em->getRepository('EnhavoShopBundle:UserAddress')->findOneBy([
            'user' => $user
        ]);
    }

    private function setAddress(AddressInterface $address, AddressInterface $lastOrderAddress)
    {
        if($address->getFirstName() === null) {
            $address->setFirstName($lastOrderAddress->getFirstName());
        }

        if($address->getLastName() === null) {
            $address->setLastName($lastOrderAddress->getLastName());
        }

        if($address->getPhoneNumber() === null) {
            $address->setPhoneNumber($lastOrderAddress->getPhoneNumber());
        }

        if($address->getCountryCode() === null) {
            $address->setCountryCode($lastOrderAddress->getCountryCode());
        }

        if($address->getProvinceCode() === null) {
            $address->setProvinceCode($lastOrderAddress->getProvinceCode());
        }

        if($address->getStreet() === null) {
            $address->setStreet($lastOrderAddress->getStreet());
        }

        if($address->getCity() === null) {
            $address->setCity($lastOrderAddress->getCity());
        }

        if($address->getPostcode() === null) {
            $address->setPostcode($lastOrderAddress->getPostcode());
        }
    }
}