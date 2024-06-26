<?php

namespace Enhavo\Bundle\PaymentBundle\Model;

use Payum\Core\Security\TokenInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

interface PaymentSecurityTokenInterface extends ResourceInterface, TokenInterface
{
}
