<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\UserIdentifier;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author blutze
 */
interface UserIdentifierProviderInterface
{
    public function getUserIdentifier(UserInterface $user): string;

    public function getUserIdentifierByPropertyValues(array $values): string;

    public function getUserIdentifierProperties(): array;
}
