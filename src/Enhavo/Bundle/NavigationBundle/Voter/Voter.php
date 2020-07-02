<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-27
 * Time: 08:51
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\Voter\VoterTypeInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Voter extends AbstractContainerType
{
    const VOTE_IN = 'in';
    const VOTE_ABSTAIN = 'abstain';
    const VOTE_OUT = 'out';

    /** @var VoterTypeInterface */
    protected $type;

    public function vote(NodeInterface $node)
    {
        $this->type->vote($node);
    }

    public function isDisabled()
    {
        $this->type->isDisabled($this->options);
    }
}
