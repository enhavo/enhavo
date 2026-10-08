<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\TwoFactor;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorTokenInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\TwoFactorFirewallContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function str_contains;

class TwoFactorLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TwoFactorFirewallContext $twoFactorFirewallContext,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::LOGIN_SUCCESS => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(UserEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TwoFactorTokenInterface) {
            return;
        }

        $config = $this->twoFactorFirewallContext->getFirewallConfig($token->getFirewallName());
        $authFormPath = $config->getAuthFormPath();
        $isRoute = !str_contains($authFormPath, '/');
        $url = $isRoute ? $this->urlGenerator->generate($authFormPath) : $authFormPath;

        $event->setResponse(new JsonResponse([
            'success' => true,
            'push' => $url,
        ]));
    }
}
