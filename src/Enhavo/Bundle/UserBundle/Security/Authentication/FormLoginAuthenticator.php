<?php
/**
 * @author blutze-media
 * @since 2020-10-26
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;


use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private ?string $loginRoute = null;

    public function __construct(
        private UserManager $userManager,
        private UserRepository $userRepository,
        private ConfigurationProvider $configurationProvider,
        private UrlGeneratorInterface $urlGenerator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private UserPasswordEncoderInterface $passwordEncoder,
        private UserMapperInterface $userMapper,
        private EventDispatcherInterface $eventDispatcher,
        string $className)
    {
    }


    public function supports(Request $request): bool
    {
        $configKey = $this->getConfigKey($request);
        $this->updateLoginRoute($configKey);
        $isRoute = $this->loginRoute === $request->attributes->get('_route');
        $isPost = $request->isMethod('POST');

        return $isRoute && $isPost;
    }

    public function getCredentials(Request $request): array
    {
        $properties = $this->userMapper->getCredentialProperties();

        $credentials = [
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        foreach ($properties as $property) {
            $credentials[$property] = $request->request->get('_' .$property);
        }

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $this->userMapper->getUsername($credentials)
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $username = $this->userMapper->getUsername($credentials);
        /** @var UserInterface $user */
        $user = $userProvider->loadUserByUsername($username);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, \Symfony\Component\Security\Core\User\UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $session = $request->getSession();
        $targetPath = $request->get('_target_path') ?? $this->getTargetPath($session, $providerKey);
        $event = $this->dispatchSuccess($user);

        if ($targetPath) {
            return $event->getResponse() ?? new RedirectResponse($targetPath);
        }

        return $event->getResponse();
    }

    /**
     * @throws Exception
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $user = ($exception instanceof AccountStatusException ?
            $exception->getUser() : $this->userRepository->findByEmail(strtolower($request->get('_email'))) // is _email always the right key?
        );
        if ($user) {
            $event = $this->dispatchFailure($user, $exception);

            if ($event->getResponse()) {
                return $event->getResponse();
            }
        }

        $this->updateLoginRoute($this->getConfigKey($request));
        return new RedirectResponse($this->getLoginUrl());
    }

    private function dispatchSuccess(UserInterface $user): UserEvent
    {
        $event = new UserEvent(UserEvent::TYPE_LOGIN_SUCCESS, $user);
        $this->eventDispatcher->dispatch($event);

        return $event;
    }

    private function dispatchFailure(UserInterface $user, AuthenticationException $exception): UserEvent
    {
        $event = new UserEvent(UserEvent::TYPE_LOGIN_FAILED, $user);
        $event->setException($exception);
        $this->eventDispatcher->dispatch($event);

        return $event;
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $configKey = $this->getConfigKey($request);
        $this->updateLoginRoute($configKey);
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    protected function updateLoginRoute($config)
    {
        if (is_string($config)) {
            try {
                $this->loginRoute = $this->configurationProvider->getLoginConfiguration($config)->getRoute();
            } catch (ConfigurationException $e) {
                // don't update login route
            }
        }
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate($this->loginRoute);
    }

    protected function getConfigKey(Request $request)
    {
        return $request->attributes->get('_config');
    }
}
