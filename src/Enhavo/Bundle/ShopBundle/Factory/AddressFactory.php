<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Sylius\Component\Addressing\Model\AddressInterface;

class AddressFactory extends Factory
{
    public function createFromAddress(AddressInterface $address): AddressInterface
    {
        $newAddress = $this->createNew();

        $newAddress->setFirstName($address->getFirstName());
        $newAddress->setLastName($address->getLastName());
        $newAddress->setPhoneNumber($address->getPhoneNumber());
        $newAddress->setCountryCode($address->getCountryCode());
        $newAddress->setProvinceCode($address->getProvinceCode());
        $newAddress->setStreet($address->getStreet());
        $newAddress->setCity($address->getCity());
        $newAddress->setPostcode($address->getPostcode());

        return $newAddress;
    }
}
