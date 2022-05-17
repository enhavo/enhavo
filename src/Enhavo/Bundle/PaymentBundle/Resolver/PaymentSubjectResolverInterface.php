<?php

namespace Enhavo\Bundle\PaymentBundle\Resolver;

use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;

interface PaymentSubjectResolverInterface
{
    public function resolve(PaymentInterface $payment);
}
