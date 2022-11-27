<?php

namespace Enhavo\Bundle\UserBundle\Model;

class Credentials implements CredentialsInterface
{
    public ?string $userIdentifier = null;
    public ?string $password = null;
    public ?string $csrfToken = null;
    public bool $rememberMe = false;

    public function getUserIdentifier(): ?string
    {
        return $this->userIdentifier;
    }

    public function setUserIdentifier(?string $userIdentifier): void
    {
        $this->userIdentifier = $userIdentifier;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getCsrfToken(): ?string
    {
        return $this->csrfToken;
    }

    public function setCsrfToken(?string $csrfToken): void
    {
        $this->csrfToken = $csrfToken;
    }

    public function isRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(?bool $rememberMe): void
    {
        $this->rememberMe = (bool)$rememberMe;
    }
}
