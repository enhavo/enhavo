<?php
/**
 * OrderAddressProvider.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Order;

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

    public function __construct(FactoryInterface $addressFactory, OrderProvider $orderProvider)
    {
        $this->addressFactory = $addressFactory;
        $this->orderProvider = $orderProvider;
    }

    public function provide(OrderInterface $order)
    {
        $user = $order->getUser();
        $address = $order->getShippingAddress();
        if ($user instanceof UserInterface) {
            if ($address === null) {
                /** @var AddressInterface $address */
                $address = $this->addressFactory->createNew();
                $order->setShippingAddress($address);
            }

            $lastOrder = $this->orderProvider->getLastOrder($user);
            if($lastOrder !== null) {
                $lastOrderAddress = $this->orderProvider->getLastOrder($user)->getShippingAddress();
                if($lastOrderAddress) {
                    $this->setAddress($address, $lastOrderAddress);
                }
            }
        }
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