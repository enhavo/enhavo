<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Voter\Type;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\NavItem\Type\LinkNavItemType;
use Enhavo\Bundle\NavigationBundle\Voter\AbstractVoterType;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;

class LinkHierarchyVoterType extends AbstractVoterType
{
    /** @var RequestStack */
    private $requestStack;

    public function vote(NodeInterface $node)
    {
        $navItem = $node->getNavItem();
        if($navItem instanceof LinkNavItemType) {
            $link = $navItem->getLink();
            if($this->match($link)) {
                return Voter::VOTE_IN;
            }
        }
        return Voter::VOTE_ABSTAIN;
    }

    private function match($url)
    {
        $request = $this->requestStack->getCurrentRequest();
        $path = sprintf('/%s', $request->getBasePath());
        $host = $request->getHost();

        $linkInfo = parse_url($url);

        if(isset($linkInfo['host']) && ($linkInfo['host'] != $host)) {
            return false;
        }

        if(isset($linkInfo['path'])) {

            $linkParts = explode('/', $linkInfo['path']);
            $pathParts = explode('/', $path);

            if(!array_diff($linkParts, $pathParts)) {
                return true;
            }
        }

        return false;
    }

    public static function getName(): ?string
    {
        return 'link_hierarchy';
    }
}
