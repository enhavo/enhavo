<?php

/**
 * UserInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Model;

use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface ApiTokenAwareInterface extends BaseUserInterface, GroupableInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    public function getApiToken(): ?string;

    public function setApiToken(?string $token): void;

    public function getApiAccess(): bool;

    public function setApiAccess(bool $apiAccess): void;

    public function getApiTokenCreatedAt(): ?\DateTime;

    public function setApiTokenCreatedAt(?\DateTime $apiTokenCreatedAt): void;
}
