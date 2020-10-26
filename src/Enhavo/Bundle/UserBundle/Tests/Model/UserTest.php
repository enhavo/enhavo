<?php


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
