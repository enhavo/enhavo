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

class TooManyLoginAttemptsSubscriber extends AbstractPreAuthSubscriber
{
    public function onPreAuth(UserEvent $event): void
    {
        if (true === $this->hasTooManyLoginAttempts($event->getUser(), $this->getLoginConfiguration())) {
            $exception = new TooManyLoginAttemptsException('Too many login attempts');
            $exception->setUser($event->getUser());
            $event->setException($exception);
        }
    }

    public function hasTooManyLoginAttempts(UserInterface $user, LoginConfiguration $configuration): bool
    {
        if ($configuration->getMaxFailedLoginAttempts()
            && $user->getFailedLoginAttempts() > $configuration->getMaxFailedLoginAttempts()
        ) {
            return true;
        }

        return false;
    }

}
