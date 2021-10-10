<?php

namespace Enhavo\Bundle\UserBundle\Form\Data;

class DeleteConfirm
{
    /** @var ?string */
    private $password;

    /** @var ?boolean */
    private $confirm;

    /** @var ?string */
    private $email;

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool|null
     */
    public function getConfirm(): ?bool
    {
        return $this->confirm;
    }

    /**
     * @param bool|null $confirm
     */
    public function setConfirm(?bool $confirm): void
    {
        $this->confirm = $confirm;
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
