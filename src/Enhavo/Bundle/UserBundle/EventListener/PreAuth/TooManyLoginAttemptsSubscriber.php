<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\TooManyLoginAttemptsException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;

abstract class TooManyLoginAttemptsSubscriber extends AbstractPreAuthSubscriber
{

    public function checkPreAuth(UserEvent $event): void
    {
        if (true === $this->hasTooManyLoginAttempts($event->getUser(), $this->getLoginConfiguration())) {
            $event->setException(new TooManyLoginAttemptsException('Too many login attempts'));
        }
    }

    public function hasTooManyLoginAttempts(UserInterface $user, LoginConfiguration $configuration): bool
    {
        if ($configuration->getMaxFailedLoginAttempts() > 0 && $user->getFailedLoginAttempts() > $configuration->getMaxFailedLoginAttempts()) {
            return true;
        }

        return false;
    }

}
