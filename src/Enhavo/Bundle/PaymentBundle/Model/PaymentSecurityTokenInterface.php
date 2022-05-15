<?php

namespace Enhavo\Bundle\PaymentBundle\Model;

use Payum\Core\Security\TokenInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface PaymentSecurityTokenInterface extends ResourceInterface, TokenInterface
{
}
