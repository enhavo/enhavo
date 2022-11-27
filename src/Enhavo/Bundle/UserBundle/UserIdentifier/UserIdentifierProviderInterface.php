<?php
/**
 * @author blutze-media
 * @since 2020-10-29
 */

namespace Enhavo\Bundle\UserBundle\UserIdentifier;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserIdentifierProviderInterface
{
    public function getUserIdentifier(UserInterface $user): string;

    public function getUserIdentifierByPropertyValues(array $values): string;

    public function getUserIdentifierProperties(): array;
}
