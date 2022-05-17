<?php

namespace Enhavo\Bundle\PaymentBundle\Entity;

use Sylius\Component\Payment\Model\PaymentMethod as BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod
{
    private ?GatewayConfig $gatewayConfig = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?string $instructions = null;

    public function getGatewayConfig(): ?GatewayConfig
    {
        return $this->gatewayConfig;
    }

    public function setGatewayConfig(?GatewayConfig $gatewayConfig): void
    {
        $this->gatewayConfig = $gatewayConfig;
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
