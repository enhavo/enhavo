<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Node\Voter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LinkVoter implements VoterInterface, TypeInterface
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
        if($node->getType() === 'link' && $content instanceof Link) {
            $link = $content->getLink();
            if($this->match($link)) {
                return VoterInterface::VOTE_IN;
            }
        }
        return VoterInterface::VOTE_ABSTAIN;
    }

    protected function match($url)
    {
        $request = $this->getRequest();
        $path = sprintf('/%s', $request->getBasePath());
        $host = $request->getHost();

        $linkInfo = parse_url($url);

        if(isset($linkInfo['host']) && ($linkInfo['host'] != $host)) {
            return false;
        }

        if(isset($linkInfo['path']) && ($linkInfo['path'] == $path)) {
            return true;
        }

        return false;
    }

    protected function getRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    public function getType()
    {
        return 'link';
    }
}