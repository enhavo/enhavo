<?php

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
