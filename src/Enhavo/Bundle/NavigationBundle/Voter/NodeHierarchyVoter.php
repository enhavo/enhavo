<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        foreach ($descendants as $descendant) {
            $vote = $this->navigationManager->isActive($descendant, [
                'exclude' => [$this],
            ]);
            if (VoterInterface::VOTE_IN == $vote) {
                return VoterInterface::VOTE_IN;
            }
        }

        return VoterInterface::VOTE_ABSTAIN;
    }
}
