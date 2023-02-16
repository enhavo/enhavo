<?php

namespace Enhavo\Bundle\MediaBundle\GarbageCollection\Voter;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class MarkedGarbageCollectionVoter implements GarbageCollectionVoterInterface
{
    private ?\DateTime $garbageTimestampThreshold = null;

    public function __construct(
        private bool $enableDeleteMarkedGarbage
    ) {}

    public function vote(FileInterface $file): string
    {
        if (!$this->enableDeleteMarkedGarbage) {
            return self::VOTE_ABSTAIN;
        }

        if ($this->garbageTimestampThreshold === null) {
            $this->garbageTimestampThreshold = new \DateTime();
            $this->garbageTimestampThreshold->modify('-2 days');
        }

        if ($file->isGarbage() && $file->getGarbageTimestamp() < $this->garbageTimestampThreshold) {
            return self::VOTE_DELETE;
        }
        return self::VOTE_ABSTAIN;
    }
}
