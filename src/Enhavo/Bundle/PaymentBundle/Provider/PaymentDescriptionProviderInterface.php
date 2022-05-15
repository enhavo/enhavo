<?php

namespace Enhavo\Bundle\PaymentBundle\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentDescriptionProviderInterface
{
    public function getPaymentDescription(PaymentInterface $payment): string;
}
