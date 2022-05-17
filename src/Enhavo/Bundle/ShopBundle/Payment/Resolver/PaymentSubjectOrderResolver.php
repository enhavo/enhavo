<?php

namespace Enhavo\Bundle\ShopBundle\Resolver;

use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Enhavo\Bundle\PaymentBundle\Resolver\PaymentSubjectResolverInterface;
use Enhavo\Bundle\ShopBundle\Entity\Payment;

class PaymentSubjectOrderResolver implements PaymentSubjectResolverInterface
{
    public function resolve(PaymentInterface $payment)
    {
        if ($payment instanceof Payment) {
            return $payment->getOrder();
        }
        return null;
    }
}
