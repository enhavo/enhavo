<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Model;

class Credentials implements CredentialsInterface
{
    public ?string $userIdentifier = null;
    public ?string $password = null;
    public ?string $csrfToken = null;
    public bool $rememberMe = false;

    public function getUserIdentifier(): ?string
    {
        return (string) $this->userIdentifier;
    }

    public function setUserIdentifier(?string $userIdentifier): void
    {
        $this->userIdentifier = (string) $userIdentifier;
    }

    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = (string) $password;
    }

    public function getCsrfToken(): ?string
    {
        return (string) $this->csrfToken;
    }

    public function setCsrfToken(?string $csrfToken): void
    {
        $this->csrfToken = (string) $csrfToken;
    }

    public function isRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(?bool $rememberMe): void
    {
        $this->rememberMe = (bool) $rememberMe;
    }
}
