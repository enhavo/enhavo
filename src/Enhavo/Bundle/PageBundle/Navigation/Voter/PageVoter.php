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
use Enhavo\Bundle\RoutingBundle\Entity\Route;
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
        if ('page' === $node->getName() && $subject instanceof Content && $subject->getContent()) {
            /** @var Page $page */
            $page = $subject->getContent();
            if ($this->match($page)) {
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
        if ($pageRoute instanceof Route) {
            $pageRouteName = $pageRoute->getName();
        }

        return null !== $pageRouteName && $routeName == $pageRouteName;
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
