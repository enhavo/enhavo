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
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FactoryInterface $endpointFactory,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $event = $this->dispatchSuccess($user);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $endpointConfig = $request->attributes->get('_endpoint');
        if ($endpointConfig) {
            /** @var Endpoint $endpoint */
            $endpoint = $this->endpointFactory->create($endpointConfig);

            return $endpoint->getResponse($request);
        }

        return null;
    }

    private function dispatchSuccess(UserInterface $user): UserEvent
    {
        $event = new UserEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvent::LOGIN_SUCCESS);

        return $event;
    }
}
