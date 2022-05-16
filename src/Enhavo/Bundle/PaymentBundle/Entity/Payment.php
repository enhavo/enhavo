<?php

namespace Enhavo\Bundle\PaymentBundle\Entity;

use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Sylius\Component\Payment\Model\Payment as BasePayment;

class Payment extends BasePayment implements PaymentInterface
{
    private ?string $token;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
