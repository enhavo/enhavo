<?php

namespace Enhavo\Bundle\MediaBundle\GarbageCollection\Voter;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface GarbageCollectionVoterInterface
{
    const VOTE_DELETE = 'delete';
    const VOTE_KEEP = 'keep';
    const VOTE_ABSTAIN = 'abstain';

    public function vote(FileInterface $file): string;
}
