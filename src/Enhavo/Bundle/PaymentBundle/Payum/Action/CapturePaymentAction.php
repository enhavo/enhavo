<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\Payment as PayumPayment;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Convert;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

final class CapturePaymentAction implements GatewayAwareInterface
{
    use GatewayAwareTrait;

    private PaymentDescriptionProviderInterface $paymentDescriptionProvider;

    public function __construct(PaymentDescriptionProviderInterface $paymentDescriptionProvider)
    {
        $this->paymentDescriptionProvider = $paymentDescriptionProvider;
    }

    /**
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var SyliusPaymentInterface $payment */
        $payment = $request->getModel();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $this->gateway->execute($status = new GetStatus($payment));
        if ($status->isNew()) {
            try {
                $this->gateway->execute($convert = new Convert($payment, 'array', $request->getToken()));
                $payment->setDetails($convert->getResult());
            } catch (RequestNotSupportedException $e) {
                $payumPayment = new PayumPayment();
                $payumPayment->setNumber($order->getNumber());
                $payumPayment->setTotalAmount($payment->getAmount());
                $payumPayment->setCurrencyCode($order->getCurrencyCode());
                $payumPayment->setClientEmail($order->getCustomer()->getEmail());
                $payumPayment->setClientId($order->getCustomer()->getId());
                $payumPayment->setDescription($this->paymentDescriptionProvider->getPaymentDescription($payment));
                $payumPayment->setDetails($payment->getDetails());

                $this->gateway->execute($convert = new Convert($payumPayment, 'array', $request->getToken()));
                $payment->setDetails($convert->getResult());
            }
        }

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        try {
            $request->setModel($details);
            $this->gateway->execute($request);
        } finally {
            $payment->setDetails((array) $details);
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof SyliusPaymentInterface
        ;
    }
}
