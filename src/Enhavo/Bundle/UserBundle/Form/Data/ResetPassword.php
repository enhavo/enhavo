<?php

namespace Enhavo\Bundle\UserBundle\Form\Data;

class ResetPassword
{
    /** @var ?string */
    private $username;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
}
