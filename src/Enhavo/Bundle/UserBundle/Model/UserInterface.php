<?php

/**
 * UserInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    public function setUsername($username);

    public function setSalt($salt);

    public function getEmail();

    public function setEmail($email);

    public function getPlainPassword();

    public function setPlainPassword($password);

    public function setPassword($password);

    public function isSuperAdmin();

    public function setEnabled($boolean);

    public function isEnabled(): bool;

    public function setSuperAdmin($boolean);

    public function getConfirmationToken();

    public function setConfirmationToken($confirmationToken);

    public function setPasswordRequestedAt(\DateTime $date = null);

    public function isPasswordRequestNonExpired($ttl);

    public function setLastLogin(\DateTime $time = null);

    public function hasRole($role);

    public function setRoles(array $roles);

    public function addRole($role);

    public function removeRole($role);

    public function isVerified(): bool;

    public function setVerified(bool $verified): void;
}
