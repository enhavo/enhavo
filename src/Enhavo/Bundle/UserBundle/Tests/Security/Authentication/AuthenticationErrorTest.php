<?php

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication;

use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage\BadCredentialsError;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
}


class AuthenticationErrorDependencies
{
    /** @var AuthenticationUtils|MockObject */
    public $authenticationUtils;

    /** @var TranslatorInterface|MockObject */
    public $translator;
}
