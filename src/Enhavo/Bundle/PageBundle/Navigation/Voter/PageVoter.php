<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.07.18
 * Time: 14:59
 */

namespace Enhavo\Bundle\PageBundle\Navigation\Voter;

use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\Voter\VoterInterface;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Component\HttpFoundation\RequestStack;

class PageVoter implements VoterInterface, TypeInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function vote(NodeInterface $node)
    {
        $content = $node->getContent();
        if($node->getType() === 'page' && $content instanceof Page) {
            if($this->match($content)) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    protected function match(Page $page)
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
        return $this->requestStack->getMasterRequest();
    }

    public function getType()
    {
        return 'page';
    }
}