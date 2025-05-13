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

use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * @author gseidel
 */
interface UserInterface extends BaseUserInterface, GroupableInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getUserIdentifier(): string;

    public function setUserIdentifier(string $userIdentifier): void;

    public function setUsername(?string $username);

    public function getEmail();

    public function setEmail(?string $email);

    public function getPlainPassword();

    public function setPlainPassword(?string $password);

    public function setPassword(?string $password);

    public function isSuperAdmin();

    public function setEnabled(bool $enabled);

    public function isEnabled(): bool;

    public function setSuperAdmin(bool $superAdmin);

    public function getConfirmationToken();

    public function setConfirmationToken(?string $confirmationToken);

    public function setPasswordRequestedAt(?\DateTime $date = null);

    public function isPasswordRequestNonExpired($ttl);

    public function setLastLogin(?\DateTime $time = null);

    public function hasRole(?string $role);

    public function setRoles(array $roles);

    public function addRole(?string $role);

    public function removeRole(?string $role);

    public function isVerified(): bool;

    public function setVerified(bool $verified): void;

    public function getFailedLoginAttempts(): int;

    public function setFailedLoginAttempts(int $failedLoginAttempts): void;

    public function setLastFailedLoginAttempt(?\DateTime $lastFailedLoginAttempt): void;

    public function getLastFailedLoginAttempt(): ?\DateTime;

    public function getPasswordUpdatedAt(): ?\DateTime;

    public function setPasswordUpdatedAt(?\DateTime $passwordUpdatedAt): void;
}
