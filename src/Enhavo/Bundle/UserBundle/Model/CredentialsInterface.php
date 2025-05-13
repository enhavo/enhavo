<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Model;

interface CredentialsInterface
{
    public function getPassword(): ?string;

    public function getCsrfToken(): ?string;

    public function getUserIdentifier(): ?string;

    public function isRememberMe(): bool;
}
