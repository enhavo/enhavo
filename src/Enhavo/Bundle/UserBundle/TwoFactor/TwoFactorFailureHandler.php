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

use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorTokenInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderRegistry;
use Scheb\TwoFactorBundle\Security\TwoFactor\TwoFactorFirewallContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use function str_contains;

class TwoFactorFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TwoFactorProviderRegistry $providerRegistry,
        private readonly TwoFactorFirewallContext $twoFactorFirewallContext,
        private readonly LogoutUrlGenerator $logoutUrlGenerator,
    ) {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TwoFactorTokenInterface) {
            throw $exception;
        }

        $providerName = $token->getCurrentTwoFactorProvider();
        $config = $this->twoFactorFirewallContext->getFirewallConfig($token->getFirewallName());
        $checkPath = $config->getCheckPath();
        $isRoute = !str_contains($checkPath, '/');

        $templateVars = [
            'twoFactorProvider'           => $token->getCurrentTwoFactorProvider(),
            'availableTwoFactorProviders' => $token->getTwoFactorProviders(),
            'authenticationError'         => $exception->getMessageKey(),
            'authenticationErrorData'     => $exception->getMessageData(),
            'displayTrustedOption'        => false,
            'authCodeParameterName'       => $config->getAuthCodeParameterName(),
            'trustedParameterName'        => $config->getTrustedParameterName(),
            'isCsrfProtectionEnabled'     => $config->isCsrfProtectionEnabled(),
            'csrfParameterName'           => $config->getCsrfParameterName(),
            'csrfTokenId'                 => $config->getCsrfTokenId(),
            'checkPathRoute'              => $isRoute ? $checkPath : null,
            'checkPathUrl'                => $isRoute ? null : $checkPath,
            'logoutPath'                  => $this->logoutUrlGenerator->getLogoutPath(),
        ];

        $renderer = $this->providerRegistry->getProvider($providerName)->getFormRenderer();

        return $renderer->renderForm($request, $templateVars);
    }
}
