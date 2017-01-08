<?php

namespace Enhavo\Bundle\ShopBundle\Shipping;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;

/**
 * ShippingTaxCalculator.php
 *
 * @since 08/01/17
 * @author gseidel
 */
class ShippingTaxCalculator implements ShippingTaxCalculatorInterface
{
    public function calculate(OrderInterface $order)
    {
        return intval($order->getShippingPrice() * 0.19);
    }
}