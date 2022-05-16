<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Request\Generic;
use Sylius\Component\Payment\Model\PaymentInterface;

class ExecuteSameRequestWithPaymentDetailsAction implements GatewayAwareInterface, ActionInterface
{
    use GatewayAwareTrait;

    /**
     * @param Generic $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        $this->gateway->execute($convert = new Convert($payment, 'array'));

        $details = ArrayObject::ensureArrayObject($convert->getResult());

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
            $request instanceof Generic &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
