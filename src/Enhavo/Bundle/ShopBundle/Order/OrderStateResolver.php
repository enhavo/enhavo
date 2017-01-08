<?php
/**
 * OrderStateResolver.php
 *
 * @since 07/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;

class OrderStateResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolvePaymentState(OrderInterface $order)
    {
        $payment = $order->getPayment();
        if($payment->getState() === PaymentInterface::STATE_COMPLETED) {
            $order->setPaymentState('completed');
        }
        return;
    }
}