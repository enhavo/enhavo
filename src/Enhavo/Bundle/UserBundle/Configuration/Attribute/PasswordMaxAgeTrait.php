<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait PasswordMaxAgeTrait
{
    private ?string $passwordMaxAge;

    public function getPasswordMaxAge(): string
    {
        return $this->passwordMaxAge ?? '';
    }

    public function setPasswordMaxAge(?string $passwordMaxAge): void
    {
        $this->passwordMaxAge = $passwordMaxAge;
    }
}
