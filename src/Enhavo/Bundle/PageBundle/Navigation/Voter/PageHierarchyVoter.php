<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.07.18
 * Time: 14:59
 */

namespace Enhavo\Bundle\PageBundle\Navigation\Voter;

use Enhavo\Bundle\NavigationBundle\Entity\Content;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageHierarchyVoter extends PageVoter
{
    public function vote(NodeInterface $node)
    {
        $subject = $node->getSubject();
        if($node->getName() === 'page' && $subject instanceof Content && $subject->getContent()) {
            /** @var Page $page */
            $page = $subject->getContent();
            $descendants = $page->getDescendants();
            foreach($descendants as $descendant) {
                if($this->match($descendant)) {
                    return VoterInterface::VOTE_IN;
                }
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }
}
