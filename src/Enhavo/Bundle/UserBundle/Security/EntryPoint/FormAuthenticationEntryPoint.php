<?php

namespace Enhavo\Bundle\UserBundle\Security\EntryPoint;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class FormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private RouterInterface $router,
        private ConfigurationProvider $configurationProvider,
    )
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $params = [];
        if ($request->query->has('view_id')) {
            $params['view_id'] = $request->query->get('view_id');
        }

        $route = $this->configurationProvider->getLoginConfiguration()->getRoute();

        return new RedirectResponse($this->router->generate($route, $params));
    }
}
