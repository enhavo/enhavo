<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.07.18
 * Time: 14:59
 */

namespace Enhavo\Bundle\PageBundle\Navigation\Voter;

use Enhavo\Bundle\NavigationBundle\Entity\Content;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Component\HttpFoundation\RequestStack;

class PageVoter implements VoterInterface
{
    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function vote(NodeInterface $node)
    {
        $subject = $node->getSubject();
        if($node->getName() === 'page' && $subject instanceof Content && $subject->getContent()) {
            /** @var Page $page */
            $page = $subject->getContent();
            if($this->match($page)) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    public function match(Page $page)
    {
        $request = $this->getRequest();
        $routeName = $request->get('_route');

        $pageRouteName = null;
        $pageRoute = $page->getRoute();
        if($pageRoute instanceof Route) {
            $pageRouteName = $pageRoute->getName();
        }

        return $pageRouteName !== null && $routeName == $pageRouteName;
    }

    protected function getRequest()
    {
        return $this->requestStack->getMainRequest();
    }

    public static function getName(): ?string
    {
        return 'page';
    }
}
