<?php
/**
 * OrderPaymentProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;


use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;

class OrderPaymentProcessor implements ProcessorInterface
{
    public function process(OrderInterface $order)
    {
        $order->getPayment()->setCurrencyCode('EUR');
        $order->getPayment()->setAmount($order->getTotal());
    }
}