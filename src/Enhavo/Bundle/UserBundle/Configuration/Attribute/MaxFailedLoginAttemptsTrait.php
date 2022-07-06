<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait MaxFailedLoginAttemptsTrait
{
    private ?int $maxFailedLoginAttempts;

    public function getMaxFailedLoginAttempts(): int
    {
        return $this->maxFailedLoginAttempts ?? 0;
    }

    public function setMaxFailedLoginAttempts(?int $maxFailedLoginAttempts): void
    {
        $this->maxFailedLoginAttempts = $maxFailedLoginAttempts;
    }
}
