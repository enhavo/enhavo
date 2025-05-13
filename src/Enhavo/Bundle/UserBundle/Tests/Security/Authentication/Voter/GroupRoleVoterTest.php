<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Model\Group;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Security\Authentication\Voter\GroupRoleVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @author blutze
 */
class GroupRoleVoterTest extends TestCase
{
    private function createInstance(): GroupRoleVoter
    {
        return new GroupRoleVoter();
    }

    public function testVoteGranted()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $group = new Group();
        $group->addRole(UserInterface::ROLE_ADMIN);
        $user->expects($this->exactly(2))->method('getGroups')->willReturn([$group]);

        $voter = $this->createInstance();
        $result = $voter->vote($token, null, [UserInterface::ROLE_ADMIN]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testVoteAbstain()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $group = new Group();
        $group->addRole(UserInterface::ROLE_SUPER_ADMIN);
        $user->expects($this->exactly(2))->method('getGroups')->willReturn([$group]);

        $voter = $this->createInstance();
        $result = $voter->vote($token, null, [UserInterface::ROLE_ADMIN]);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }
}
