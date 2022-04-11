<?php

namespace Enhavo\Bundle\ShopBundle\Taxation\Applicator;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

interface OrderTaxesApplicatorInterface
{
    public function apply(OrderInterface $order): void;
}
