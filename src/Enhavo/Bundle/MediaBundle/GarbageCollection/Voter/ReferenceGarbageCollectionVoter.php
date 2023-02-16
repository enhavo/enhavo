<?php

namespace Enhavo\Bundle\MediaBundle\GarbageCollection\Voter;

use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class ReferenceGarbageCollectionVoter implements GarbageCollectionVoterInterface
{
    public function __construct(
        private AssociationFinder $associationFinder,
        private bool $enableDeleteUnreferenced,
    ) {}

    public function vote(FileInterface $file): string
    {
        if (!$this->enableDeleteUnreferenced) {
            return self::VOTE_ABSTAIN;
        }

        // Prevent deleting files that have just been uploaded and just aren't connected yet
        if ($file->isGarbage()) {
            return self::VOTE_ABSTAIN;
        }

        $associations = $this->associationFinder->findAssociationsTo($file, FileInterface::class, [Format::class]);
        if (count($associations) > 0) {
            return self::VOTE_KEEP;
        } else {
            return self::VOTE_DELETE;
        }
    }
}
