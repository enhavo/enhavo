<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\GarbageCollection\Voter;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface GarbageCollectionVoterInterface
{
    public const VOTE_DELETE = 'delete';
    public const VOTE_KEEP = 'keep';
    public const VOTE_ABSTAIN = 'abstain';

    public function vote(FileInterface $file): string;
}
