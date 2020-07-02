<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 19:15
 */

namespace Enhavo\Bundle\NavigationBundle\Tests\Voter;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\Voter\NodeHierarchyVoter;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

class NodeHierarchyVoterTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new NodeHierarchyVoterDependencies();
        $dependencies->navigationManager = $this->getMockBuilder(NavigationManager::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(NodeHierarchyVoterDependencies $dependencies)
    {
        return new NodeHierarchyVoter($dependencies->navigationManager);
    }

    public function testVoteIn()
    {
        $dependencies = $this->createDependencies();
        $dependencies->navigationManager->method('isActive')->willReturn(true);
        $voter = $this->createInstance($dependencies);

        $parentNode = new Node();
        $node = new Node();

        $parentNode->addChild($node);

        $this->assertEquals(VoterInterface::VOTE_IN, $voter->vote($parentNode));
    }

    public function testVoteAbstain()
    {
        $dependencies = $this->createDependencies();
        $dependencies->navigationManager->method('isActive')->willReturn(false);
        $voter = $this->createInstance($dependencies);

        $parentNode = new Node();
        $node = new Node();

        $parentNode->addChild($node);

        $this->assertEquals(VoterInterface::VOTE_ABSTAIN, $voter->vote($parentNode));
    }
}

class NodeHierarchyVoterDependencies
{
    /** @var NavigationManager|\PHPUnit_Framework_MockObject_MockObject */
    public $navigationManager;
}
