<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security;


use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Security\UserChecker;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserCheckerTest extends TestCase
{
    private function createDependencies(): UserCheckerTestDependencies
    {
        $dependencies = new UserCheckerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(UserCheckerTestDependencies $dependencies): UserChecker
    {
        return new UserChecker(
            $dependencies->eventDispatcher
        );
    }

    public function testPreAuth()
    {
        $dependencies = $this->createDependencies();
        $checker = $this->createInstance($dependencies);
        $user = new UserMock();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) {
            $this->assertInstanceOf(UserEvent::class, $event);

            return $event;
        });
        $checker->checkPreAuth($user);
    }

    public function testPreAuthError()
    {
        $dependencies = $this->createDependencies();
        $checker = $this->createInstance($dependencies);
        $user = new UserMock();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function (UserEvent $event) {
            $event->setException(new AuthenticationException('__TEST__'));

            return $event;
        });
        $this->expectException(AuthenticationException::class);
        $checker->checkPreAuth($user);
    }
}

class UserCheckerTestDependencies
{
    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
}
