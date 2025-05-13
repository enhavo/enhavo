<?php

namespace Enhavo\Bundle\UserBundle\Tests\Mocks;

use Enhavo\Bundle\UserBundle\Model\User;

/**
 * @author blutze
 */
class UserMock extends User
{
    /** @var int|null */
    public $customerId;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    /**
     * @param int|null $customerId
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->customerId = $customerId;
    }
}
