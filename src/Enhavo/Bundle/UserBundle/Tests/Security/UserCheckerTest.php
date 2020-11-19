<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security;


use Enhavo\Bundle\UserBundle\Exception\AccountDisabledException;
use Enhavo\Bundle\UserBundle\Exception\UserNotSupportedException;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Security\UserChecker;
use PHPUnit\Framework\TestCase;

class UserCheckerTest extends TestCase
{
    public function testCheckPreAuthEnabled()
    {
        $user = new User();
        $user->setEnabled(true);
        $checker = new UserChecker();

        $this->assertNull($checker->checkPreAuth($user));
    }

    public function testCheckPreAuthDisabled()
    {
        $user = new User();
        $user->setEnabled(false);
        $checker = new UserChecker();

        $this->expectException(AccountDisabledException::class);
        $checker->checkPreAuth($user);
    }

    public function testCheckPreAuthType()
    {
        $user = new \Symfony\Component\Security\Core\User\User('user1', 'pw');
        $checker = new UserChecker();

        $this->expectException(UserNotSupportedException::class);
        $checker->checkPreAuth($user);
    }

    public function testCheckPostAuthEnabled()
    {
        $user = new User();
        $user->setEnabled(true);
        $checker = new UserChecker();

        $this->assertNull($checker->checkPostAuth($user));
    }

    public function testCheckPostAuthDisabled()
    {
        $user = new User();
        $user->setEnabled(false);
        $checker = new UserChecker();

        $this->expectException(AccountDisabledException::class);
        $checker->checkPostAuth($user);
    }

    public function testCheckPostAuthType()
    {
        $user = new \Symfony\Component\Security\Core\User\User('user1', 'pw');
        $checker = new UserChecker();

        $this->expectException(UserNotSupportedException::class);
        $checker->checkPostAuth($user);
    }
}
