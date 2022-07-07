<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Enhavo\Bundle\UserBundle\Exception\TooManyLoginAttemptsException;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserAuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager           $userManager,
        private ConfigurationProvider $configurationProvider,
        private RequestStack          $requestStack,
        private RouterInterface       $router,
        private TranslatorInterface   $translator,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::class => 'onUserEvent',
        ];
    }

    /**
     * @throws Exception
     */
    public function onUserEvent(UserEvent $event)
    {
        if ($event->getType() === UserEvent::TYPE_PASSWORD_CHANGED) {
            $this->onPasswordChanged($event);

        } else if ($event->getType() === UserEvent::TYPE_LOGIN_SUCCESS) {
            $this->onLogin($event);

        } else if ($event->getType() === UserEvent::TYPE_LOGIN_FAILED) {
            $this->onLoginFailure($event);
        }
    }

    /**
     * @throws Exception
     */
    private function onLogin(UserEvent $event): void
    {
        $user = $event->getUser();

        $user->setLastLogin(new \DateTime());
        $user->setFailedLoginAttempts(0);
        $user->setLastFailedLoginAttempt(null);
        $this->userManager->update($user);
    }

    /**
     * @throws Exception
     */
    private function onLoginFailure(UserEvent $event): void
    {
        $user = $event->getUser();
        $configKey = $this->getConfigKey();
        $exception = $this->getRequest()->getSession()->get(Security::AUTHENTICATION_ERROR);

        if ($exception instanceof BadCredentialsException) {
            $user->setFailedLoginAttempts(1 + $user->getFailedLoginAttempts());
            $user->setLastFailedLoginAttempt(new \DateTime());
            $this->userManager->update($user);

        } else if ($exception instanceof PasswordExpiredException) {
            $resetConfiguration = $this->configurationProvider->getResetPasswordRequestConfiguration($configKey);

            if (null === $user->getConfirmationToken()) {
                $this->userManager->resetPassword($user, $resetConfiguration);
            }

            $url = $this->generateUrl($resetConfiguration->getRedirectRoute());

            $this->addError($this->getRequest()->getSession(), 'login.error.password_expired');
            $event->setResponse(new RedirectResponse($url));

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

    private function onPasswordChanged(UserEvent $event)
    {
        $user = $event->getUser();
        $user->setPasswordUpdatedAt(new \DateTime());
        $this->userManager->update($user);
    }

    private function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    private function getConfigKey()
    {
        return $this->getRequest()->attributes->get('_config');
    }

    private function addError(Session $session, string $message)
    {
        $session->getFlashBag()->add('error', $this->translator->trans($message, [], 'EnhavoUserBundle'));
    }

    private function generateUrl(string $route, array $options = []): string
    {
        return $this->router->generate($route, $options);
    }
}
