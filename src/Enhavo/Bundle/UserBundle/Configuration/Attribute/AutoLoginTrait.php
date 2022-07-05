<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait AutoLoginTrait
{
    private ?bool $autoLogin;

    public function isAutoLogin(): ?bool
    {
        return $this->autoLogin;
    }

    public function setAutoLogin(?bool $autoLogin): void
    {
        $this->autoLogin = $autoLogin;
    }
}
