<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Event\UserLoginEvent;
use Enhavo\Bundle\UserBundle\Event\UserLoginFailureEvent;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
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
            UserLoginEvent::class => 'onLogin',
            UserLoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    /**
     * @throws Exception
     */
    public function onLogin(UserLoginEvent $event): void
    {
        $user = $event->getUser();
        $configKey = $this->getConfigKey();
        $configuration = $this->configurationProvider->getLoginConfiguration($configKey);
        $updated = $user->getPasswordUpdatedAt();

        $user->setLastLogin(new \DateTime());
        $user->setFailedLoginAttempts(0);
        $user->setLastFailedLoginAttempt(null);

        if ($configuration->getPasswordMaxAge() &&
            (!$updated || $updated->modify(sprintf('+%s', $configuration->getPasswordMaxAge())) < new \DateTime())
        ) {
            // todo: This can be handled by PasswordMaxAgeResetHandler
            $this->userManager->logout();

            $user->setPlainPassword(random_bytes(12));

            $configuration = $this->configurationProvider->getResetPasswordRequestConfiguration($configKey);
            $this->userManager->resetPassword($user, $configuration);
            $url = $this->generateUrl($configuration->getRedirectRoute());

            $this->addError($this->getRequest()->getSession(), 'login.error.password_expired');
            $event->setResponse(new RedirectResponse($url));

        } else {
            $this->userManager->update($user);
        }
    }

    /**
     * @throws Exception
     */
    public function onLoginFailure(UserLoginFailureEvent $event): void
    {
        $user = $event->getUser();

        if ($user) {
            $configKey = $this->getConfigKey();
            $configuration = $this->configurationProvider->getLoginConfiguration($configKey);
            if ($configuration->getMaxFailedLoginAttempts() > 0) {

                $user->setFailedLoginAttempts(1 + $user->getFailedLoginAttempts());
                $user->setLastFailedLoginAttempt(new \DateTime());

                if ($user->getFailedLoginAttempts() > $configuration->getMaxFailedLoginAttempts()) {
                    // todo: this can be handled by LoginFailureResetHandler
                    $user->setPlainPassword(random_bytes(12));
                    $user->setFailedLoginAttempts(0);
                    $user->setLastFailedLoginAttempt(null);

                    $resetConfiguration = $this->configurationProvider->getResetPasswordRequestConfiguration($configKey);
                    $this->userManager->resetPassword($user, $resetConfiguration);

                    $this->addError($this->getRequest()->getSession(), 'login.error.max_failed_attempts');
                    $url = $this->generateUrl($resetConfiguration->getRedirectRoute());
                    $event->setResponse(new RedirectResponse($url));

                } else {
                    $this->userManager->update($user);
                }
            }
        }
    }

    public function onUserEvent(UserEvent $event)
    {
        if ($event->getType() === UserEvent::TYPE_PASSWORD_CHANGED) {
            /** @var User $user */
            $user = $event->getUser();
            $user->setPasswordUpdatedAt(new \DateTime());
            $this->userManager->update($user);
        }
    }

    private function getRequest(): Request
    {
        return $this->requestStack->getMainRequest();
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
