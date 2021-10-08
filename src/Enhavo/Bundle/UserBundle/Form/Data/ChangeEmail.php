<?php

namespace Enhavo\Bundle\UserBundle\Form\Data;

class ChangeEmail
{
    /** @var ?string */
    private $currentPassword;

    /** @var ?string */
    private $email;

    /**
     * @return string|null
     */
    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    /**
     * @param string|null $currentPassword
     */
    public function setCurrentPassword(?string $currentPassword): void
    {
        $this->currentPassword = $currentPassword;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
