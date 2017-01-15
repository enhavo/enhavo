<?php
/**
 * OrderPurchaseProcessor.php
 *
 * @since 02/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class OrderPurchaseProcessor
{
    use ContainerAwareTrait;

    public function process(OrderInterface $order)
    {
        $payment = $order->getPayment();

        if($payment->getMethod()->getGateway() === 'paypal_express_checkout') {
            $details = $payment->getDetails();
            $details['PAYMENTREQUEST_0_AMT'] = (string)($order->getTotal()/100);
            $details['PAYMENTREQUEST_0_CURRENCYCODE'] = $payment->getCurrencyCode();

            $details['PAYMENTREQUEST_0_SHIPTONAME'] = sprintf('%s %s %s',
                $order->getShippingAddress()->getFirstName(),
                $order->getShippingAddress()->getLastName(),
                $order->getShippingAddress()->getCompany()
            );
            $details['PAYMENTREQUEST_0_SHIPTOSTREET'] = $order->getShippingAddress()->getStreet();
            $details['PAYMENTREQUEST_0_SHIPTOSTREET2'] = '';
            $details['PAYMENTREQUEST_0_SHIPTOCITY'] = $order->getShippingAddress()->getCity();
            $details['PAYMENTREQUEST_0_SHIPTOSTATE'] = '';
            $details['PAYMENTREQUEST_0_SHIPTOZIP'] = $order->getShippingAddress()->getPostcode();
            $details['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $order->getShippingAddress()->getCountryCode();
            $details['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = '';
            $details['NOSHIPPING'] = '1';

            $logo = $this->container->getParameter('enhavo_shop.payment.paypal.logo');
            if($logo) {
                $details['LOGOIMG'] = $logo;
            }

            $branding = $this->container->getParameter('enhavo_shop.payment.paypal.branding');
            if($branding) {
                $details['BRANDNAME'] = $branding;
            }

            $payment->setDetails($details);
        }
    }
}