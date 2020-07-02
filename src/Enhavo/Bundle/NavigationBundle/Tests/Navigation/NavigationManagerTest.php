<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 17:24
 */

namespace Enhavo\Bundle\NavigationBundle\Tests\Navigation;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

class NavigationManagerTest extends TestCase
{
    private function createInstance()
    {
        return new NavigationManager();
    }

    public function testIsActiveTrue()
    {
        $manager = $this->createInstance();
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_ABSTAIN));

        $node = new Node();
        $this->assertTrue($manager->isActive($node));
    }

    public function testIsActiveFalse()
    {
        $manager = $this->createInstance();
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_OUT));
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_ABSTAIN));

        $node = new Node();
        $this->assertFalse($manager->isActive($node));
    }

    public function testIsActiveAllAbstain()
    {
        $manager = $this->createInstance();
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_ABSTAIN));

        $node = new Node();
        $this->assertFalse($manager->isActive($node));
    }

    public function testIsActiveExcludeOptionByClass()
    {
        $manager = $this->createInstance();
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));

        $node = new Node();
        $this->assertFalse($manager->isActive($node, [
            'exclude' => [VoterMock::class]
        ]));
    }

    public function testIsActiveExcludeOptionByObject()
    {
        $manager = $this->createInstance();
        $voter = new VoterMock(VoterInterface::VOTE_IN);
        $manager->addVoter($voter);

        $node = new Node();
        $this->assertFalse($manager->isActive($node, [
            'exclude' => [$voter]
        ]));
    }

    public function testIsActiveVoterOptionByClass()
    {
        $manager = $this->createInstance();
        $manager->addVoter(new VoterMock(VoterInterface::VOTE_IN));
        $manager->addVoter(new OtherVoterMock());

        $node = new Node();
        $this->assertTrue($manager->isActive($node, [
            'voters' => [VoterMock::class]
        ]));
    }

    public function testIsActiveVoterOptionByObject()
    {
        $manager = $this->createInstance();
        $voterIn = new VoterMock(VoterInterface::VOTE_IN);
        $voterOut = new VoterMock(VoterInterface::VOTE_OUT);
        $manager->addVoter($voterIn);
        $manager->addVoter($voterOut);

        $node = new Node();
        $this->assertTrue($manager->isActive($node, [
            'voters' => [$voterIn]
        ]));
    }
}

class VoterMock implements VoterInterface
{
    /** @var string */
    private $vote;

    /**
     * VoterMock constructor.
     * @param string $vote
     */
    public function __construct(string $vote)
    {
        $this->vote = $vote;
    }

    public function vote(NodeInterface $node)
    {
        return $this->vote;
    }
}

class OtherVoterMock implements VoterInterface
{
    public function vote(NodeInterface $node)
    {
        return VoterInterface::VOTE_OUT;
    }
}
