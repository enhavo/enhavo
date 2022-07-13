<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use DateTime;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\TooManyLoginAttemptsException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Contracts\Translation\TranslatorInterface;

class TooManyLoginAttemptsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager,
        private ConfigurationProvider $configurationProvider,
        private RequestStack $requestStack,
        private RouterInterface $router,
        private TranslatorInterface $translator,
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
    public function onUserEvent(UserEvent $userEvent): void
    {
        if ($userEvent->getType() === UserEvent::TYPE_PRE_AUTH) {
            $this->onPreAuth($userEvent);
        } else if ($userEvent->getType() === UserEvent::TYPE_LOGIN_FAILED) {
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
            $user->setLastFailedLoginAttempt(new DateTime());
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

    protected function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function getConfigKey()
    {
        return $this->getRequest()->attributes->get('_config');
    }

    protected function addError(Session $session, string $message)
    {
        $session->getFlashBag()->add('error', $this->translator->trans($message, [], 'EnhavoUserBundle'));
    }

    protected function generateUrl(string $route, array $options = []): string
    {
        return $this->router->generate($route, $options);
    }

    protected function getLoginConfiguration(): LoginConfiguration
    {
        return $this->configurationProvider->getLoginConfiguration($this->getConfigKey());
    }
}
