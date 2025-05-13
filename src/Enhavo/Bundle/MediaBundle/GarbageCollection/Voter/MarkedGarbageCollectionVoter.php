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

class MarkedGarbageCollectionVoter implements GarbageCollectionVoterInterface
{
    private ?\DateTime $garbageTimestampThreshold = null;

    public function __construct(
        private bool $enableDeleteMarkedGarbage,
    ) {
    }

    public function vote(FileInterface $file): string
    {
        if (!$this->enableDeleteMarkedGarbage) {
            return self::VOTE_ABSTAIN;
        }

        if (null === $this->garbageTimestampThreshold) {
            $this->garbageTimestampThreshold = new \DateTime();
            $this->garbageTimestampThreshold->modify('-2 days');
        }

        if ($file->isGarbage() && $file->getGarbageTimestamp() < $this->garbageTimestampThreshold) {
            return self::VOTE_DELETE;
        }

        return self::VOTE_ABSTAIN;
    }
}
