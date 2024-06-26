<?php

namespace Enhavo\Bundle\PaymentBundle\Model;

use Payum\Core\Model\GatewayConfigInterface as BaseGatewayConfigInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

interface GatewayConfigInterface extends BaseGatewayConfigInterface, ResourceInterface
{
}
