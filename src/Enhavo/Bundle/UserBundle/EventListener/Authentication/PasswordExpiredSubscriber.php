<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\Authentication;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;

class PasswordExpiredSubscriber extends AbstractFailureSubscriber
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

        if ($exception instanceof PasswordExpiredException) {
            $resetConfiguration = $this->configurationProvider->getResetPasswordRequestConfiguration($configKey);

            if (null === $user->getConfirmationToken()) {
                $this->userManager->resetPassword($user, $resetConfiguration);
            }

            $url = $this->generateUrl($resetConfiguration->getRedirectRoute());

            $this->addError($this->getRequest()->getSession(), 'login.error.password_expired');

            $event->setResponse(new RedirectResponse($url));
        }
    }

}
