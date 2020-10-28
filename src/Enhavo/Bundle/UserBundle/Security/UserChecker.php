<?php
/**
 * @author blutze-media
 * @since 2020-10-28
 */

namespace Enhavo\Bundle\UserBundle\Security;

use Enhavo\Bundle\UserBundle\Exception\AccountDisabledException;
use Enhavo\Bundle\UserBundle\Exception\UserNotSupportedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface as EnhavoUser;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof EnhavoUser) {
            throw new UserNotSupportedException(sprintf('User has to be of type "%s"', EnhavoUser::class ));
        }

        if (false === $user->isEnabled()) {
            throw new AccountDisabledException('User account not enabled');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof EnhavoUser) {
            throw new UserNotSupportedException(sprintf('User has to be of type "%s"', EnhavoUser::class ));
        }
    }
}
