<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

interface VoterInterface
{
    public const VOTE_IN = 'in';
    public const VOTE_ABSTAIN = 'abstain';
    public const VOTE_OUT = 'out';

    public function vote(NodeInterface $node);
}
