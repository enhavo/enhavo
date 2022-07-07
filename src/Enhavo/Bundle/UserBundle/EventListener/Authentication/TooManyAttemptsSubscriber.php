<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\Authentication;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\TooManyLoginAttemptsException;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;

class TooManyAttemptsSubscriber extends AbstractFailureSubscriber
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
        $configKey = $this->getConfigKey();
        $exception = $event->getException();

        if ($exception instanceof BadCredentialsException) {
            $user->setLastFailedLoginAttempt(new \DateTime());
            $user->setFailedLoginAttempts(1 + $user->getFailedLoginAttempts());
            $this->userManager->update($user);

        } else if ($exception instanceof TooManyLoginAttemptsException) {
            $resetConfiguration = $this->configurationProvider->getResetPasswordRequestConfiguration($configKey);

            if (null === $user->getConfirmationToken()) {
                $this->userManager->resetPassword($user, $resetConfiguration);
            }

            $this->addError($this->getRequest()->getSession(), 'login.error.max_failed_attempts');
            $url = $this->generateUrl($resetConfiguration->getRedirectRoute());

            $event->setResponse(new RedirectResponse($url));
        }
    }
}
