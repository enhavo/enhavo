<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security;


use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Security\UserChecker;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserCheckerTest extends TestCase
{
    private function createDependencies(): UserCheckerTestDependencies
    {
        $dependencies = new UserCheckerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $dependencies->requestStack->method('getCurrentRequest')->willReturnCallback(function () {
            $request = new Request();
            $request->attributes->set('_config', '__CONFIG__');

            return $request;
        });
        return $dependencies;
    }

    private function createInstance(UserCheckerTestDependencies $dependencies): UserChecker
    {
        return new UserChecker(
            $dependencies->userManager,
            $dependencies->configurationProvider,
            $dependencies->requestStack
        );
    }

    public function testCheckPreAuthEnabled()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->expects($this->once())->method('getLoginConfiguration');
        $user = new User();
        $user->setEnabled(true);
        $checker = $this->createInstance($dependencies);

        $checker->checkPreAuth($user);
    }

    public function testCheckPreAuthDisabled()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->expects($this->once())->method('getLoginConfiguration');
        $dependencies->userManager->expects($this->once())->method('checkPreAuth');

        $user = new User();
        $user->setEnabled(false);
        $checker = $this->createInstance($dependencies);

        $checker->checkPreAuth($user);
    }

//    public function testCheckPostAuthEnabled()
//    {
//        $dependencies = $this->createDependencies();
//        $dependencies->configurationProvider->expects($this->once())->method('getLoginConfiguration');
//        $user = new User();
//        $user->setEnabled(true);
//        $checker = $this->createInstance($dependencies);
//
//        $checker->checkPostAuth($user);
//    }

    public function testCheckPostAuthDisabled()
    {
        $dependencies = $this->createDependencies();
        $user = new User();
        $user->setEnabled(false);
        $checker = $this->createInstance($dependencies);

        $this->expectException(BadCredentialsException::class);
        $checker->checkPostAuth($user);
    }
}

class UserCheckerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var RequestStack|MockObject */
    public $requestStack;
}
