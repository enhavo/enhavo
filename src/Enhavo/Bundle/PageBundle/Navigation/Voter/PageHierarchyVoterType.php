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
use Enhavo\Bundle\NavigationBundle\Voter\AbstractVoterType;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageHierarchyVoterType extends AbstractVoterType
{
    /** @var PageVoterType */
    protected $parent;

    public function vote(NodeInterface $node)
    {
        $subject = $node->getSubject();
        if($node->getName() === 'page' && $subject instanceof Content && $subject->getContent()) {
            /** @var Page $page */
            $page = $subject->getContent();
            $descendants = $page->getDescendants();
            foreach($descendants as $descendant) {
                /** @var parent PageVoterType */
                if($this->parent->match($descendant)) {
                    return Voter::VOTE_IN;
                }
            }
        }
        return Voter::VOTE_ABSTAIN;
    }

    public static function getParentType(): ?string
    {
        return PageVoterType::class;
    }

    public static function getName(): ?string
    {
        return 'page_hierarchy';
    }
}
