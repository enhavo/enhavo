<?php

namespace Enhavo\Bundle\MediaLibraryBundle\GarbageCollection\Voter;

use Enhavo\Bundle\MediaBundle\GarbageCollection\Voter\GarbageCollectionVoterInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class LibraryGarbageCollectionVoter implements GarbageCollectionVoterInterface
{
    public function vote(FileInterface $file): string
    {
        if ($file->isLibrary()) {
            return self::VOTE_KEEP;
        }
        return self::VOTE_ABSTAIN;
    }
}
