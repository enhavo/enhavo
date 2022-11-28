<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Login;

use Enhavo\Bundle\UserBundle\Configuration\Attribute\FormTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\MaxFailedLoginAttemptsTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\RedirectRouteTrait;
use Enhavo\Bundle\UserBundle\Configuration\Attribute\TemplateTrait;

class LoginConfiguration
{
    use TemplateTrait;
    use RedirectRouteTrait;
    use FormTrait;

    private ?string $route = null;
    private ?int $maxFailedLoginAttempts;
    private ?string $passwordMaxAge;
    private bool $verificationRequired = false;
    private ?string $repositoryMethod;

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

    public function getRepositoryMethod(): ?string
    {
        return $this->repositoryMethod;
    }

    public function setRepositoryMethod(?string $repositoryMethod): void
    {
        $this->repositoryMethod = $repositoryMethod;
    }
}
