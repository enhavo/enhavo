<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Sylius\Component\Payment\Factory\PaymentFactoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface;

class PaymentFactory extends Factory implements PaymentFactoryInterface
{
    public function __construct(
        string $className,
        private TokenGeneratorInterface $tokenGenerator,
    )
    {
        parent::__construct($className);
    }

    public function createNew()
    {
        /** @var Payment $payment */
        $payment = parent::createNew();
        $payment->setToken($this->tokenGenerator->generateToken());
        return $payment;
    }

    public function createWithAmountAndCurrencyCode(int $amount, string $currency): PaymentInterface
    {
        $payment = $this->createNew();
        $payment->setAmount($amount);
        $payment->setCurrencyCode($currency);

        return $payment;
    }
}
