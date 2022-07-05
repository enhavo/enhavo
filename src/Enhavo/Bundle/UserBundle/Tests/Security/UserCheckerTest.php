<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security;


use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

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

        $this->expectException(BadCredentialsException::class);
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

        $this->expectException(BadCredentialsException::class);
        $checker->checkPostAuth($user);
    }
}
