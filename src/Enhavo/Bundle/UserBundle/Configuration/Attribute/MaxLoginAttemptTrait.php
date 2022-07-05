<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait MaxLoginAttemptTrait
{
    private ?int $maxLoginAttempts;

    public function getMaxLoginAttempts(): int
    {
        return $this->maxLoginAttempts ?? 0;
    }

    public function setMaxLoginAttempts(?int $maxLoginAttempts): void
    {
        $this->maxLoginAttempts = $maxLoginAttempts;
    }
}
