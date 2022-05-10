<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Entity\PaymentMethod;
use Enhavo\Bundle\ShopBundle\Payment\Provider\PaymentMethodTypeProvider;

class PaymentMethodFactory extends Factory
{
    public function __construct(
        string $dataClass,
        private PaymentMethodTypeProvider $provider,
    )
    {
        parent::__construct($dataClass);
    }

    public function createWithGatewayType(string $type)
    {
        $paymentMethodType = $this->provider->getType($type);

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->createNew();
        $paymentMethod->setGatewayType($paymentMethodType->getType());
        $paymentMethod->setFactoryName($paymentMethodType->getGatewayFactory());

        return $paymentMethod;
    }
}
