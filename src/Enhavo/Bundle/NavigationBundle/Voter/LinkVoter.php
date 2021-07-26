<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LinkVoter implements VoterInterface
{
    /** @var RequestStack */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function vote(NodeInterface $node)
    {
        $subject = $node->getSubject();
        if($subject instanceof Link) {
            $link = $subject->getLink();
            if($this->match($link)) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    protected function match($url)
    {
        $request = $this->requestStack->getCurrentRequest();
        $requestInfo = parse_url($request->getRequestUri());

        $linkInfo = parse_url($url);

        if(isset($linkInfo['host']) && ($linkInfo['host'] != $request->getHost())) {
            return false;
        }

        if(isset($linkInfo['path']) && ($linkInfo['path'] == $requestInfo['path'])) {
            return true;
        }

        return false;
    }
}
