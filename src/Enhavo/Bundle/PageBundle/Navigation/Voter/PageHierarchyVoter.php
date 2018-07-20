<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.07.18
 * Time: 14:59
 */

namespace Enhavo\Bundle\PageBundle\Navigation\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\Voter\VoterInterface;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageHierarchyVoter extends PageVoter
{
    public function vote(NodeInterface $node)
    {
        $content = $node->getContent();
        if($node->getType() === 'page' && $content instanceof Page) {
            $descendants  = $content->getDescendants();
            foreach($descendants as $descendant) {
                if($this->match($descendant)) {
                    return VoterInterface::VOTE_IN;
                }
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    public function getType()
    {
        return 'page_hierarchy';
    }
}