<?php

namespace Enhavo\Bundle\UserBundle\Twig;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var ConfigurationProvider */
    private $configurationProvider;

    /** @var RouterInterface */
    private $router;

    /**
     * TwigExtension constructor.
     * @param CsrfTokenManagerInterface $tokenManager
     * @param ConfigurationProvider $configurationProvider
     * @param RouterInterface $router
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, ConfigurationProvider $configurationProvider, RouterInterface $router)
    {
        $this->tokenManager = $tokenManager;
        $this->configurationProvider = $configurationProvider;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('user_verify_path', [$this, 'getVerifyPath']),
        ];
    }

    public function getVerifyPath($key = 'theme')
    {
        $token = $this->tokenManager->getToken('user-verification');
        $route = $this->configurationProvider->getVerificationRequestConfiguration($key)->getRoute();

        return $this->router->generate($route, [
            'csrfToken' => $token
        ]);
    }
}
