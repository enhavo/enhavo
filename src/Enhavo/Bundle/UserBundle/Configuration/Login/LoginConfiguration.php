<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Login;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class LoginConfiguration
{
    use TemplateTrait;
    use RedirectRouteTrait;
    use FormTrait;

    private ?string $route = null;
    private ?string $checkRoute = null;
    private ?int $maxFailedLoginAttempts = null;
    private ?string $passwordMaxAge = null;
    private bool $verificationRequired = false;

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }

    public function getMaxFailedLoginAttempts(): int
    {
        return $this->maxFailedLoginAttempts ?? 0;
    }

    public function setMaxFailedLoginAttempts(?int $maxFailedLoginAttempts): void
    {
        $this->maxFailedLoginAttempts = $maxFailedLoginAttempts;
    }

    public function getPasswordMaxAge(): ?string
    {
        return $this->passwordMaxAge;
    }

    public function setPasswordMaxAge(?string $passwordMaxAge): void
    {
        $this->passwordMaxAge = $passwordMaxAge;
    }

    public function isVerificationRequired(): bool
    {
        return $this->verificationRequired;
    }

    public function setVerificationRequired(bool $verificationRequired): void
    {
        $this->verificationRequired = $verificationRequired;
    }

    public function getCheckRoute(): ?string
    {
        return $this->checkRoute;
    }

    public function setCheckRoute(?string $checkRoute): void
    {
        $this->checkRoute = $checkRoute;
    }
}
