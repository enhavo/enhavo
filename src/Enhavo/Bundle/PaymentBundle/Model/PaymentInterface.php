<?php

namespace Enhavo\Bundle\PaymentBundle\Model;

use Sylius\Component\Payment\Model\PaymentInterface as SyliusPayment;

interface PaymentInterface extends SyliusPayment
{
    public function getToken(): ?string;
    public function setToken(?string $token): void;
}
