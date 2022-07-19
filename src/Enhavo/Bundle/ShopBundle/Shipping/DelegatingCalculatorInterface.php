<?php

namespace Enhavo\Bundle\ShopBundle\Shipping;

use Sylius\Component\Shipping\Calculator\DelegatingCalculatorInterface as SyliusDelegatingCalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

interface DelegatingCalculatorInterface extends SyliusDelegatingCalculatorInterface
{
    public function calculateTax(ShipmentInterface $subject): int;
}
