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

class UserTest extends TestCase
{
    private function createInstance()
    {
        return new User();
    }

    public function test()
    {
        $user = $this->createInstance();
        $user->setAdmin(true);

        $this->assertTrue($user->isAdmin());
        $user->setAdmin(false);
        $this->assertFalse($user->isAdmin());
        $user->setSuperAdmin(true);
        $this->assertTrue($user->isSuperAdmin());
        $user->setSuperAdmin(false);
        $this->assertFalse($user->isSuperAdmin());

        $group = new Group();
        $group->setName('_test');
        $user->addGroup($group);
        $this->assertContains($group, $user->getGroups());
        $this->assertContains($group->getName(), $user->getGroupNames());
        $user->removeGroup($group);
        $this->assertNotContains($group, $user->getGroups());
    }
}
