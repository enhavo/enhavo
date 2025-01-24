<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class CsrfDisableFormProtectionBadge implements BadgeInterface
{
    private bool $resolved = false;

    public function markResolved(): void
    {
        $this->resolved = true;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
