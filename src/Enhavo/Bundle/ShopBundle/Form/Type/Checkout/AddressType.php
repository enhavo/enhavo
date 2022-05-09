<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Enhavo\Bundle\ShopBundle\Form\Type\AddressSubjectType;
use Symfony\Component\Form\AbstractType;

class AddressType extends AbstractType
{
    public function getParent()
    {
        return AddressSubjectType::class;
    }
}
