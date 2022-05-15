<?php

namespace Enhavo\Bundle\PaymentBundle\Entity;

use Payum\Core\Model\GatewayConfig as BaseGatewayConfig;
use Sylius\Component\Resource\Model\ResourceInterface;

class GatewayConfig extends BaseGatewayConfig implements ResourceInterface
{
    private ?int $id = null;
    private ?string $gatewayType = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getGatewayType(): ?string
    {
        return $this->gatewayType;
    }

    public function setGatewayType(?string $gatewayType): void
    {
        $this->gatewayType = $gatewayType;
    }
}
