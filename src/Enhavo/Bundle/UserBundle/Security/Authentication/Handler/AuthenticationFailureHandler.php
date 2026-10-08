<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication\Handler;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Model\CredentialsInterface;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $userLoader = null;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FactoryInterface $endpointFactory,
        private readonly ConfigurationProvider $configurationProvider,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    public function setUserLoader(callable $userLoader): void
    {
        $this->userLoader = $userLoader;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $credentials = $this->getCredentials($request);

        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
            $request->getSession()->set('_security.credentials', $credentials);
        }

        $user = $exception->getToken()?->getUser();

        if (null === $user && null !== $this->userLoader && null !== $credentials?->getUserIdentifier()) {
            try {
                $user = ($this->userLoader)($credentials->getUserIdentifier());
            } catch (\Exception) {
            }
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

    private function getCredentials(Request $request): ?CredentialsInterface
    {
        $loginConfiguration = $this->configurationProvider->getLoginConfiguration();
        $form = $this->formFactory->create($loginConfiguration->getFormClass(), null, $loginConfiguration->getFormOptions());
        $form->handleRequest($request);
        $data = $form->getData();

        return $data instanceof CredentialsInterface ? $data : null;
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
