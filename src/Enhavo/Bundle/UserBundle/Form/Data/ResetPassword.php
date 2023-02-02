<?php

namespace Enhavo\Bundle\UserBundle\Form\Data;

class ResetPassword
{
    private ?string $userIdentifier = null;

    public function getUserIdentifier(): ?string
    {
        return $this->userIdentifier;
    }

    public function setUserIdentifier(?string $userIdentifier): void
    {
        $this->userIdentifier = $userIdentifier;
    }
}
