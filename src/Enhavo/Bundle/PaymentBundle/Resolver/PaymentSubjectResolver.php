<?php

namespace Enhavo\Bundle\PaymentBundle\Resolver;

use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;

class PaymentSubjectResolver implements PaymentSubjectResolverInterface
{
    public function resolve(PaymentInterface $payment)
    {
        return null;
    }
}
