<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:06
 */

namespace Enhavo\Bundle\NavigationBundle\Node\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

interface VoterInterface
{
    const VOTE_IN = 'in';
    const VOTE_ABSTAIN = 'abstain';
    const VOTE_OUT = 'out';

    public function vote(NodeInterface $node);
}