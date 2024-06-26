<?php

namespace Enhavo\Bundle\PaymentBundle\Entity;

use Payum\Core\Model\Token;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class PaymentSecurityToken extends Token implements ResourceInterface
{
    private ?int $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
