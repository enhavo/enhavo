<?php

namespace Enhavo\Bundle\ShopBundle\Shipping;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

/**
 * ShippingTaxCalculatorInterface.php
 *
 * @since 08/01/17
 * @author gseidel
 */
interface ShippingTaxCalculatorInterface
{
    public function calculate(OrderInterface $order);
}