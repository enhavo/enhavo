<?php

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Security\EntryPoint\FormAuthenticationEntryPoint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FormAuthenticationEntryPointTest extends TestCase
{
    private function createInstance(FormAuthenticationEntryPointDependencies $dependencies)
    {
        return new FormAuthenticationEntryPoint(
            $dependencies->router,
            $dependencies->configurationProvider,
        );
    }

    private function createDependencies(): FormAuthenticationEntryPointDependencies
    {
        $dependencies = new FormAuthenticationEntryPointDependencies();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->query = new ParameterBag();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->router->method('generate')->willReturnCallback(function ($route) {
            return $route.'.generated';
        });
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    public function testStart()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function() {
            $configuration = new LoginConfiguration();
            $configuration->setRoute('config.login.route');
            $configuration->setCheckRoute('config.login.route');
            return $configuration;
        });

        $response = $instance->start($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('config.login.route.generated', $response->getTargetUrl());
    }
}

class FormAuthenticationEntryPointDependencies
{
    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var RouterInterface|MockObject */
    public $router;

    /** @var Request|MockObject */
    public $request;
}
