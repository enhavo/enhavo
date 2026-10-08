<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Model\CredentialsInterface;
use Enhavo\Bundle\UserBundle\Security\Authentication\Handler\AuthenticationFailureHandler;
use Enhavo\Bundle\UserBundle\Security\Authentication\Handler\AuthenticationSuccessHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @author gseidel
 * @author blutze
 */
class FormLoginAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly ConfigurationProvider $configurationProvider,
        private readonly FormFactoryInterface $formFactory,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthenticationSuccessHandler $successHandler,
        private readonly AuthenticationFailureHandler $failureHandler,
        string $className,
    ) {
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
        $credentials = $this->getCredentials($request);

        $rememberMeBadge = new RememberMeBadge();
        $credentials->isRememberMe() ? $rememberMeBadge->enable() : $rememberMeBadge->disable();

        // check if user is already authenticated
        $user = $this->tokenStorage->getToken()?->getUser();
        if ($user instanceof UserInterface && $user->getUserIdentifier() === $credentials->getUserIdentifier()) {
            return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()), [$rememberMeBadge]);
        }

        $tokenBadge = new CsrfTokenBadge('authenticate', $credentials->getCsrfToken());

        return new Passport(
            new UserBadge($credentials->getUserIdentifier()),
            new PasswordCredentials($credentials->getPassword()),
            [$rememberMeBadge, $tokenBadge],
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
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }
}
