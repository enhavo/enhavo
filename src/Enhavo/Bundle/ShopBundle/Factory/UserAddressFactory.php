<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Entity\UserAddress;

class UserAddressFactory extends Factory
{
    public function __construct(
        string $className,
        private AddressFactory $addressFactory)
    {
        parent::__construct($className);
    }

    public function createNew()
    {
        /** @var UserAddress $userAddress */
        $userAddress = parent::createNew();

        $userAddress->setShippingAddress($this->addressFactory->createNew());
        $userAddress->setBillingAddress($this->addressFactory->createNew());

        return $userAddress;
    }
}
