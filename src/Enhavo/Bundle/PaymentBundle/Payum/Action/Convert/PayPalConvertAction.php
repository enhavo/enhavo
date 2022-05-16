<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Action\Convert;

use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Enhavo\Bundle\PaymentBundle\Payum\Request\Enhance;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;

class PayPalConvertAction implements ActionInterface
{
    /**
     * @param Convert $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = $payment->getDetails();

        $details['PAYMENTREQUEST_0_CURRENCYCODE'] = $payment->getCurrencyCode();
        $details['PAYMENTREQUEST_0_AMT'] = $this->formatPrice($payment->getAmount());

        $this->execute($enhance = new Enhance($details));

        $request->setResult($enhance->getDetails());
    }

    private function formatPrice(int $price): float
    {
        return round($price / 100, 2);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array'
            ;
    }
}
