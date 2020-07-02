<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Voter\Type;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\Voter\AbstractVoterType;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;

class NodeHierarchyVoterType extends AbstractVoterType
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
                'exclude' => ['node_hierarchy']
            ]);
            if($vote == Voter::VOTE_IN) {
                return Voter::VOTE_IN;
            }
        }
        return Voter::VOTE_ABSTAIN;
    }

    public static function getName(): ?string
    {
        return 'node_hierarchy';
    }
}
