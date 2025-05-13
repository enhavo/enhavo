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

interface ApiTokenAwareInterface extends BaseUserInterface, GroupableInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    public function getApiToken(): ?string;

    public function setApiToken(?string $token): void;

    public function getApiAccess(): bool;

    public function setApiAccess(bool $apiAccess): void;

    public function getApiTokenCreatedAt(): ?\DateTime;

    public function setApiTokenCreatedAt(?\DateTime $apiTokenCreatedAt): void;
}
