<?php

namespace Enhavo\Bundle\PaymentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Enhavo\Bundle\PaymentBundle\Entity\PaymentMethod;
use Payum\Core\Model\GatewayConfigInterface;
use Payum\Core\Payum;
use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Payum\Core\Request\Capture;

class PaymentManager
{
    public function __construct(
        protected EntityManagerInterface $em,
        private Payum $payum,
    )
    {}

    public function reset(Payment $payment)
    {
        $payment->setState(PaymentInterface::STATE_NEW);
        $payment->setDetails([]);
        $this->em->flush();
    }

    public function complete(Payment $payment)
    {
        if ($payment->getState() === PaymentInterface::STATE_AUTHORIZED) {
            $this->capture($payment);
        }
    }

    public function capture(Payment $payment)
    {
        $gatewayConfig = $this->getGatewayConfig($payment);
        $gateway = $this->payum->getGateway($gatewayConfig->getGatewayName());
        $gateway->execute($capture = new Capture($payment));
    }

    private function getGatewayConfig(Payment $payment): GatewayConfigInterface
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $payment->getMethod();

        return $paymentMethod->getGatewayConfig();
    }
}
