<?php
/**
 * @author blutze-media
 * @author gseidel
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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class FormLoginAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UserManager $userManager,
        private UserRepository $userRepository,
        private ConfigurationProvider $configurationProvider,
        private UrlGeneratorInterface $urlGenerator,
        private UserMapperInterface $userMapper,
        private EventDispatcherInterface $eventDispatcher,
        string $className)
    {
    }

    public function supports(Request $request): bool
    {
        try {
            $loginRoute = $this->configurationProvider->getLoginConfiguration()->getRoute();
        } catch (ConfigurationException $exception) {
            return false;
        }

        $isRoute = $loginRoute === $request->attributes->get('_route');
        $isPost = $request->isMethod('POST');

        return $isRoute && $isPost;
    }

    public function authenticate(Request $request)
    {
        $credentials = $this->getCredentials($request);

        return new Passport(
            new UserBadge($credentials['username'], [$this->userRepository, 'loadUserByIdentifier']),
            new PasswordCredentials($credentials['password']),
            [
                new RememberMeBadge(),
                new CsrfTokenBadge('authenticate', $credentials['csrf_token'])
            ],
        );
    }

    private function getCredentials(Request $request): array
    {
        $usernameCredentials = [];
        $properties = $this->userMapper->getCredentialProperties();
        foreach ($properties as $property) {
            $usernameCredentials[$property] = $request->request->get('_' .$property);
        }
        $username = $this->userMapper->getUsername($usernameCredentials);

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return [
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
            'username' => $username,
        ];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $session = $request->getSession();
        $targetPath = $request->get('_target_path') ?? $this->getTargetPath($session, $firewallName);
        $event = $this->dispatchSuccess($user);

        $this->removeTargetPath($session, $firewallName);

        if ($targetPath) {
            return $event->getResponse() ?? new RedirectResponse($targetPath);
        }

        return $event->getResponse();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $user = $exception->getToken()?->getUser();

        if ($user === null) {
            $credentials = $this->getCredentials($request);
            $user = $this->userRepository->loadUserByIdentifier($credentials['username']);
        }

        if ($user) {
            $event = $this->dispatchFailure($user, $exception);

            if ($event->getResponse()) {
                return $event->getResponse();
            }
        }

        return new RedirectResponse($this->getLoginUrl());
    }

    private function dispatchSuccess(UserInterface $user): UserEvent
    {
        $event = new UserEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvent::LOGIN_SUCCESS);

        return $event;
    }

    private function dispatchFailure(UserInterface $user, AuthenticationException $exception): UserEvent
    {
        $event = new UserEvent($user);
        $event->setException($exception);
        $this->eventDispatcher->dispatch($event, UserEvent::LOGIN_FAILURE);

        return $event;
    }

    private function getLoginUrl(): string
    {
        $loginRoute = $this->configurationProvider->getLoginConfiguration()->getRoute();
        return $this->urlGenerator->generate($loginRoute);
    }
}
