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

use Enhavo\Bundle\UserBundle\Exception\UserIdentifierException;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailUserIdentifierProvider implements UserIdentifierProviderInterface
{
    public function getUserIdentifier(UserInterface $user): string
    {
        if ($user instanceof \Enhavo\Bundle\UserBundle\Model\UserInterface) {
            return $user->getEmail();
        }

        throw new UserIdentifierException(sprintf('Type "%s" not supported', get_class($user)));
    }

    public function getUserIdentifierByPropertyValues(array $values): string
    {
        return $values['email'];
    }

    public function getUserIdentifierProperties(): array
    {
        return ['email'];
    }
}
