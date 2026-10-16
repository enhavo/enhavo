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

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Security\Authentication\Handler\AuthenticationSuccessHandler;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationSuccessHandlerTest extends TestCase
{
    private function createInstance(AuthenticationSuccessHandlerTestDependencies $dependencies): AuthenticationSuccessHandler
    {
        return new AuthenticationSuccessHandler(
            $dependencies->eventDispatcher,
            $dependencies->endpointFactory,
        );
    }

    private function createDependencies(): AuthenticationSuccessHandlerTestDependencies
    {
        $dependencies = new AuthenticationSuccessHandlerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->endpointFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();

        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag();

        return $dependencies;
    }

    public function testAuthenticationSuccess()
    {
        $user = new UserMock();
        $dependencies = $this->createDependencies();

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals($user, $event->getUser());
            $this->assertEquals(UserEvent::LOGIN_SUCCESS, $name);
            $event->setResponse(new RedirectResponse('/admin/dashboard'));

            return $event;
        });

        $instance = $this->createInstance($dependencies);

        /** @var TokenInterface|MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $response = $instance->onAuthenticationSuccess($dependencies->request, $token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/admin/dashboard', $response->getTargetUrl());
    }

    public function testAuthenticationSuccessNoResponse()
    {
        $user = new UserMock();
        $dependencies = $this->createDependencies();

        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $name) {
            return $event;
        });

        $instance = $this->createInstance($dependencies);

        /** @var TokenInterface|MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->method('getUser')->willReturn($user);

        $response = $instance->onAuthenticationSuccess($dependencies->request, $token);

        $this->assertNull($response);
    }
}

class AuthenticationSuccessHandlerTestDependencies
{
    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;

    /** @var FactoryInterface|MockObject */
    public $endpointFactory;

    /** @var Request|MockObject */
    public $request;
}
