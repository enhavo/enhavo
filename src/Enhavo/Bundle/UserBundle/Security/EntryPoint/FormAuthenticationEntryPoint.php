<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\EntryPoint;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public function __construct(
        private RouterInterface $router,
        private ConfigurationProvider $configurationProvider,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $params = [
            'redirect' => $request->getRequestUri(),
        ];
        $route = $this->configurationProvider->getLoginConfiguration()->getRoute();

        return new RedirectResponse($this->router->generate($route, $params));
    }
}
