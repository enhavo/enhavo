<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication\Handler;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Model\Credentials;
use Enhavo\Bundle\UserBundle\Security\Authentication\Handler\AuthenticationFailureHandler;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationFailureHandlerTest extends TestCase
{
    private function createInstance(AuthenticationFailureHandlerTestDependencies $dependencies): AuthenticationFailureHandler
    {
        return new AuthenticationFailureHandler(
            $dependencies->eventDispatcher,
            $dependencies->endpointFactory,
            $dependencies->configurationProvider,
            $dependencies->urlGenerator,
            $dependencies->formFactory,
        );
    }

    private function createDependencies(): AuthenticationFailureHandlerTestDependencies
    {
        $dependencies = new AuthenticationFailureHandlerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->endpointFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $dependencies->urlGenerator->method('generate')->willReturnCallback(function ($route) {
            return $route.'.generated';
        });

        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag();
        $dependencies->request->request = new InputBag();
        $dependencies->session = $this->getMockBuilder(Session::class)->getMock();
        $dependencies->request->method('getSession')->willReturn($dependencies->session);
        $dependencies->request->method('hasSession')->willReturn(true);
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->form = $this->getMockBuilder(Form::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function testAuthenticationFailure()
    {
        $dependencies = $this->createDependencies();

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function () {
            $loginConfiguration = new LoginConfiguration();
            $loginConfiguration->setRoute('login_route');
            $loginConfiguration->setFormClass('formClass');
            $loginConfiguration->setFormOptions([]);

            return $loginConfiguration;
        });

        $dependencies->formFactory->method('create')->willReturn($dependencies->form);

        $credentials = new Credentials();
        $credentials->setUserIdentifier('1337.user@enhavo.com');
        $dependencies->form->method('getData')->willReturn($credentials);

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) {
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::LOGIN_FAILURE, $name);

            return $event;
        });

        $instance = $this->createInstance($dependencies);
        $response = $instance->onAuthenticationFailure($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('login_route.generated', $response->getTargetUrl());
    }

    public function testAuthenticationFailureWithUserFromLoader()
    {
        $dependencies = $this->createDependencies();

        $user = new UserMock();
        $user->setUserIdentifier('1337.user@enhavo.com');

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function () {
            $loginConfiguration = new LoginConfiguration();
            $loginConfiguration->setRoute('login_route');
            $loginConfiguration->setFormClass('formClass');
            $loginConfiguration->setFormOptions([]);

            return $loginConfiguration;
        });

        $dependencies->formFactory->method('create')->willReturn($dependencies->form);

        $credentials = new Credentials();
        $credentials->setUserIdentifier('1337.user@enhavo.com');
        $dependencies->form->method('getData')->willReturn($credentials);

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::LOGIN_FAILURE, $name);
            $this->assertEquals($user, $event->getUser());

            return $event;
        });

        $instance = $this->createInstance($dependencies);
        $instance->setUserLoader(function ($identifier) use ($user) {
            return $user->getUserIdentifier() === $identifier ? $user : null;
        });

        $response = $instance->onAuthenticationFailure($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('login_route.generated', $response->getTargetUrl());
    }
}

class AuthenticationFailureHandlerTestDependencies
{
    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;

    /** @var FactoryInterface|MockObject */
    public $endpointFactory;

    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var UrlGeneratorInterface|MockObject */
    public $urlGenerator;

    /** @var Request|MockObject */
    public $request;

    /** @var Session|MockObject */
    public $session;

    /** @var FormFactoryInterface|MockObject */
    public $formFactory;

    /** @var Form|MockObject */
    public $form;
}
