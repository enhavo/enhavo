<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Node\Voter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\NodeManager;

class NodeHierarchyVoter implements VoterInterface, TypeInterface
{
    /**
     * @var NodeManager;
     */
    private $nodeManager;

    public function __construct(NodeManager $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    public function vote(NodeInterface $node)
    {
        $descendants = $node->getDescendants();
        foreach($descendants as $descendant) {
            $vote = $this->nodeManager->isActive($descendant, [
                'exclude' => ['node_hierarchy']
            ]);
            if($vote == VoterInterface::VOTE_IN) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    public function getType()
    {
        return 'node_hierarchy';
    }
}