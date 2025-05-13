<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\Model;

use Enhavo\Bundle\UserBundle\Model\Group;
use Enhavo\Bundle\UserBundle\Model\User;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    private function createInstance()
    {
        return new Group();
    }

    public function test()
    {
        $group = $this->createInstance();
        $group->setName('_test');
        $group->addRole(User::ROLE_SUPER_ADMIN);
        $this->assertContains(User::ROLE_SUPER_ADMIN, $group->getRoles());
        $group->removeRole(User::ROLE_SUPER_ADMIN);
        $this->assertNotContains(User::ROLE_SUPER_ADMIN, $group->getRoles());

        $user = new User();
        $group->addUser($user);
        $this->assertContains($user, $group->getUsers());
        $group->removeUser($user);
        $this->assertNotContains($user, $group->getUsers());

        $this->assertEquals($group->getName(), (string) $group);
    }
}
