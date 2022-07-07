<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\Authentication;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Exception;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;

class BadCredentialSubscriber extends AbstractFailureSubscriber
{
    /**
     * @throws Exception
     */
    public function onUserEvent(UserEvent $userEvent): void
    {
        if ($userEvent->getType() === UserEvent::TYPE_LOGIN_FAILED) {
            $this->onLoginFailure($userEvent);
        }
    }

    /**
     * @throws Exception
     */
    private function onLoginFailure(UserEvent $event): void
    {
        $user = $event->getUser();
        $exception = $event->getException();

        if ($exception instanceof BadCredentialsException) {
            $user->setFailedLoginAttempts(1 + $user->getFailedLoginAttempts());
            $user->setLastFailedLoginAttempt(new \DateTime());
            $this->userManager->update($user);
        }
    }

}
