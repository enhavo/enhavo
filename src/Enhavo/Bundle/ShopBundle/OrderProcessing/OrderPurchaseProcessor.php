<?php
/**
 * OrderPurchaseProcessor.php
 *
 * @since 02/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class OrderPurchaseProcessor
{
    public function process(OrderInterface $order)
    {
        $payment = $order->getPayment();

        if($payment->getMethod()->getGateway() === 'paypal_express_checkout') {
            $details = $payment->getDetails();
            $details['PAYMENTREQUEST_0_AMT'] = (string)($order->getTotal()/100);
            $payment->setDetails($details);
        }
    }
}