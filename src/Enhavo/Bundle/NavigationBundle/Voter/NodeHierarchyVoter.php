<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;

class NodeHierarchyVoter implements VoterInterface
{
    /** @var NavigationManager */
    private $navigationManager;

    public function __construct(NavigationManager $navigationManager)
    {
        $this->navigationManager = $navigationManager;
    }

    public function vote(NodeInterface $node)
    {
        $descendants = $node->getDescendants();
        foreach($descendants as $descendant) {
            $vote = $this->navigationManager->isActive($descendant, [
                'exclude' => [$this]
            ]);
            if($vote == VoterInterface::VOTE_IN) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }
}
