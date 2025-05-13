<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
