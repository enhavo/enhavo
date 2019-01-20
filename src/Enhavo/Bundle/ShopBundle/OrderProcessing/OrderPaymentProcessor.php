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
use Enhavo\Bundle\ShopBundle\Order\OrderCheckoutStates;

class OrderPaymentProcessor implements ProcessorInterface
{
    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_PAYMENT_SELECTED);
        $order->getPayment()->setCurrencyCode('EUR');
        $order->getPayment()->setAmount($order->getTotal());
    }
}
