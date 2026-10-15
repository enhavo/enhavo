<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication;

use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage\BadCredentialsError;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationErrorTest extends TestCase
{
    private function createInstance(AuthenticationErrorDependencies $dependencies): AuthenticationError
    {
        return new AuthenticationError(
            $dependencies->authenticationUtils,
            $dependencies->translator,
            $dependencies->requestStack,
        );
    }

    private function createDependencies(): AuthenticationErrorDependencies
    {
        $dependencies = new AuthenticationErrorDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message.'.translated';
        });
        $dependencies->authenticationUtils = $this->getMockBuilder(AuthenticationUtils::class)->disableOriginalConstructor()->getMock();
        $dependencies->requestStack = new RequestStack();

        return $dependencies;
    }

    public function testBadCredentialsError()
    {
        $dependencies = $this->createDependencies();
        $dependencies->authenticationUtils->method('getLastAuthenticationError')->willReturn(new BadCredentialsException());
        $instance = $this->createInstance($dependencies);

        $instance->addErrorMessage(new BadCredentialsError($dependencies->translator));

        $message = $instance->getError();
        $this->assertEquals('login.error.credentials.translated', $message);
    }

    public function testErrorIsKeptForSameRequest()
    {
        $dependencies = $this->createDependencies();
        $dependencies->authenticationUtils->method('getLastAuthenticationError')->willReturnOnConsecutiveCalls(new BadCredentialsException(), null);
        $dependencies->requestStack->push(new Request());
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('login.error.general.translated', $instance->getError());
        $this->assertEquals('login.error.general.translated', $instance->getError());
    }

    public function testErrorIsNotKeptForOtherRequest()
    {
        $dependencies = $this->createDependencies();
        $dependencies->authenticationUtils->method('getLastAuthenticationError')->willReturnOnConsecutiveCalls(new BadCredentialsException(), null);
        $dependencies->requestStack->push(new Request());
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('login.error.general.translated', $instance->getError());

        $dependencies->requestStack->pop();
        $dependencies->requestStack->push(new Request());

        $this->assertNull($instance->getError());
    }

    public function testEmptyErrorIsNotKept()
    {
        $dependencies = $this->createDependencies();
        $dependencies->authenticationUtils->method('getLastAuthenticationError')->willReturnOnConsecutiveCalls(null, new BadCredentialsException());
        $dependencies->requestStack->push(new Request());
        $instance = $this->createInstance($dependencies);

        $this->assertNull($instance->getError());
        $this->assertEquals('login.error.general.translated', $instance->getError());
    }
}

class AuthenticationErrorDependencies
{
    /** @var AuthenticationUtils|MockObject */
    public $authenticationUtils;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var RequestStack */
    public $requestStack;
}
