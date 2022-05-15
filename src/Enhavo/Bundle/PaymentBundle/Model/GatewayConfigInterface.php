<?php

namespace Enhavo\Bundle\PaymentBundle\Model;

use Payum\Core\Model\GatewayConfigInterface as BaseGatewayConfigInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface GatewayConfigInterface extends BaseGatewayConfigInterface, ResourceInterface
{
}
