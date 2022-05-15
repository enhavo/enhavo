<?php

namespace Enhavo\Bundle\PaymentBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\PaymentBundle\Entity\GatewayConfig;
use Enhavo\Bundle\PaymentBundle\Entity\PaymentMethod;
use Enhavo\Bundle\PaymentBundle\Provider\PaymentMethodTypeProvider;
use Sylius\Component\Resource\Factory\FactoryInterface;

class PaymentMethodFactory extends Factory
{
    public function __construct(
        string $dataClass,
        private PaymentMethodTypeProvider $provider,
        private FactoryInterface $gatewayConfigFactory,
    )
    {
        parent::__construct($dataClass);
    }

    public function createWithGatewayType(string $type)
    {
        $paymentMethodType = $this->provider->getType($type);

        /** @var GatewayConfig $gatewayConfig */
        $gatewayConfig = $this->gatewayConfigFactory->createNew();
        $gatewayConfig->setGatewayType($paymentMethodType->getType());
        $gatewayConfig->setFactoryName($paymentMethodType->getGatewayFactory());
        $gatewayConfig->setGatewayName($paymentMethodType->getType());

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->createNew();
        $paymentMethod->setGatewayConfig($gatewayConfig);

        return $paymentMethod;
    }
}
