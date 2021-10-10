<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait AutoLoginTrait
{
    /** @var ?boolean */
    private $autoLogin;

    /**
     * @return bool|null
     */
    public function isAutoLogin(): ?bool
    {
        return $this->autoLogin;
    }

    /**
     * @param bool|null $autoLogin
     */
    public function setAutoLogin(?bool $autoLogin): void
    {
        $this->autoLogin = $autoLogin;
    }
}
