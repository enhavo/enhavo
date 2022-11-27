<?php

namespace Enhavo\Bundle\UserBundle\Model;

interface CredentialsInterface
{
    public function getPassword(): ?string;

    public function getCsrfToken(): ?string;

    public function getUserIdentifier(): ?string;

    public function isRememberMe(): bool;
}
