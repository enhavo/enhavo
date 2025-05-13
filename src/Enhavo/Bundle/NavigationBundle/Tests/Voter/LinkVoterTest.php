<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Tests\Voter;

use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Voter\LinkVoter;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LinkVoterTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new LinkVoterDependencies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(LinkVoterDependencies $dependencies)
    {
        return new LinkVoter($dependencies->requestStack);
    }

    private function createNode($url)
    {
        $node = new Node();
        $link = new Link();
        $link->setLink($url);
        $node->setSubject($link);

        return $node;
    }

    public function testVoteIn()
    {
        $dependencies = $this->createDependencies();

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->method('getRequestUri')->willReturn('/test');
        $dependencies->requestStack->method('getCurrentRequest')->willReturn($request);

        $voter = $this->createInstance($dependencies);

        $this->assertEquals(VoterInterface::VOTE_IN, $voter->vote($this->createNode('/test')));
    }

    public function testVoteAbstain()
    {
        $dependencies = $this->createDependencies();

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->method('getRequestUri')->willReturn('/something');
        $dependencies->requestStack->method('getCurrentRequest')->willReturn($request);

        $voter = $this->createInstance($dependencies);

        $this->assertEquals(VoterInterface::VOTE_ABSTAIN, $voter->vote($this->createNode('/test')));
    }

    public function testVoteNotSupported()
    {
        $dependencies = $this->createDependencies();
        $voter = $this->createInstance($dependencies);

        $this->assertEquals(VoterInterface::VOTE_ABSTAIN, $voter->vote(new Node()));
    }
}

class LinkVoterDependencies
{
    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
}
