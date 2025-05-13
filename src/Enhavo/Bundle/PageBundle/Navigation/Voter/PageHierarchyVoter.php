<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        if ('page' === $node->getName() && $subject instanceof Content && $subject->getContent()) {
            /** @var Page $page */
            $page = $subject->getContent();
            $descendants = $page->getDescendants();
            foreach ($descendants as $descendant) {
                if ($this->match($descendant)) {
                    return VoterInterface::VOTE_IN;
                }
            }
        }

        return VoterInterface::VOTE_ABSTAIN;
    }
}
