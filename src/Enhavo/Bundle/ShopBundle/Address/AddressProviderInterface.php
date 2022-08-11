<?php

namespace Enhavo\Bundle\ShopBundle\Address;

use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;

interface AddressProviderInterface
{
    public function getAddress(): ?AddressSubjectInterface;
}
