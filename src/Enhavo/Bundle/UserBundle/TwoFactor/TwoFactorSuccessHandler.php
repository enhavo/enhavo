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

use Enhavo\Bundle\UserBundle\User\TargetPathResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class TwoFactorSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly TargetPathResolver $targetPathResolver,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        return new JsonResponse([
            'success' => true,
            'redirect' => $this->targetPathResolver->resolveTargetPath($request),
        ]);
    }
}
