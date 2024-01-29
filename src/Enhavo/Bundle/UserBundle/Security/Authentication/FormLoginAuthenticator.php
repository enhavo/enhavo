<?php
/**
 * @author blutze-media
 * @author gseidel
 * @since 2020-10-26
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Model\CredentialsInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class FormLoginAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepository $userRepository,
        private ConfigurationProvider $configurationProvider,
        private UrlGeneratorInterface $urlGenerator,
        private EventDispatcherInterface $eventDispatcher,
        private FormFactoryInterface $formFactory,
        private FactoryInterface $endpointFactory,
        string $className
    )
    {
    }

    public function supports(Request $request): bool
    {
        try {
            $loginRoute = $this->configurationProvider->getLoginConfiguration()->getCheckRoute();
        } catch (ConfigurationException $exception) {
            return false;
        }

        $isRoute = $loginRoute === $request->attributes->get('_route');
        $isPost = $request->isMethod('POST');

        return $isRoute && $isPost;
    }

    public function authenticate(Request $request): Passport
    {
        $loginConfiguration = $this->configurationProvider->getLoginConfiguration();

        $credentials = $this->getCredentials($request);

        $rememberMeBadge = new RememberMeBadge();
        $credentials->isRememberMe() ? $rememberMeBadge->enable(): $rememberMeBadge->disable();

        $tokenBadge = new CsrfTokenBadge('authenticate', $credentials->getCsrfToken());

        return new Passport(
            new UserBadge($credentials->getUserIdentifier(), [$this->userRepository, $loginConfiguration->getRepositoryMethod()]),
            new PasswordCredentials($credentials->getPassword()),
            [ $rememberMeBadge, $tokenBadge ],
        );
    }

    private function getCredentials(Request $request): CredentialsInterface
    {
        $loginConfiguration = $this->configurationProvider->getLoginConfiguration();

        $form = $this->formFactory->create($loginConfiguration->getFormClass(), null, $loginConfiguration->getFormOptions());
        $form->handleRequest($request);
        $credentials = $form->getData();

        if (!$credentials instanceof CredentialsInterface) {
            throw new \InvalidArgumentException();
        }

        return $credentials;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $event = $this->dispatchSuccess($user);

        $endpointConfig = $request->attributes->get('_endpoint');
        if ($endpointConfig) {
            /** @var Endpoint $endpoint */
            $endpoint = $this->endpointFactory->create($endpointConfig);
            return $endpoint->getResponse($request);
        }

        return $event->getResponse();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $loginConfiguration = $this->configurationProvider->getLoginConfiguration();
        $credentials = $this->getCredentials($request);

        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
            $request->getSession()->set('_security.credentials', $credentials);
        }

        $user = $exception->getToken()?->getUser();

        if ($user === null) {
            $user = call_user_func([$this->userRepository, $loginConfiguration->getRepositoryMethod()], $credentials->getUserIdentifier());
        }

        $event = $this->dispatchFailure($user, $exception);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $endpointConfig = $request->attributes->get('_endpoint');
        if ($endpointConfig) {
            /** @var Endpoint $endpoint */
            $endpoint = $this->endpointFactory->create($endpointConfig);
            return $endpoint->getResponse($request);
        }

        return new RedirectResponse($this->getLoginUrl());
    }

    private function dispatchSuccess(UserInterface $user): UserEvent
    {
        $event = new UserEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvent::LOGIN_SUCCESS);

        return $event;
    }

    private function dispatchFailure(?UserInterface $user, AuthenticationException $exception): UserEvent
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
