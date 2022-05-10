<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Payum\Core\Model\GatewayConfigInterface;
use Sylius\Component\Payment\Model\PaymentMethod as BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod implements GatewayConfigInterface
{
    private ?string $gatewayType = null;
    private ?string $gatewayName = null;
    private ?string $factoryName = null;
    private ?array $config = [];
    private ?string $name = null;
    private ?string $description = null;
    private ?string $instructions = null;

    public function getGatewayType(): ?string
    {
        return $this->gatewayType;
    }

    public function setGatewayType(?string $gatewayType): void
    {
        $this->gatewayType = $gatewayType;
    }

    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    public function setGatewayName($gatewayName)
    {
        $this->gatewayName = $gatewayName;
    }

    public function getFactoryName()
    {
        return $this->factoryName;
    }

    public function setFactoryName($name)
    {
        $this->factoryName = $name;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): void
    {
        $this->instructions = $instructions;
    }
}
