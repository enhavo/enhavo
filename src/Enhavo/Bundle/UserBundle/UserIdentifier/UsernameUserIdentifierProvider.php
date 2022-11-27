<?php
/**
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\UserIdentifier;

use Enhavo\Bundle\UserBundle\Exception\UserIdentifierException;
use Symfony\Component\Security\Core\User\UserInterface;

class UsernameUserIdentifierProvider implements UserIdentifierProviderInterface
{
    public function getUserIdentifier(UserInterface $user): string
    {
        if ($user instanceof \Enhavo\Bundle\UserBundle\Model\UserInterface) {
            return $user->getUsername();
        }

        throw new UserIdentifierException(sprintf('Type "%s" not supported', get_class($user)));
    }

    public function getUserIdentifierByPropertyValues(array $values): string
    {
        return $values['username'];
    }

    public function getUserIdentifierProperties(): array
    {
        return ['username'];
    }
}
