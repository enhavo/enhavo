<?php

namespace Enhavo\Bundle\MediaBundle\GarbageCollection;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\MediaBundle\GarbageCollection\Voter\GarbageCollectionVoterInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class GarbageCollector implements GarbageCollectorInterface
{
    /** @var GarbageCollectionVoterInterface[] */
    private array $voters = [];

    private ?DateTime $garbageCheckTimestamp = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityRepository $fileRepository,
        protected bool $enabled,
        protected int $maxItemsPerRun,
    ) {}

    /**
     * @inheritDoc
     */
    public function run(?int $maxItems = null, bool $andFlush = true): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->garbageCheckTimestamp = new DateTime();

        $limit = $maxItems === null ? $this->maxItemsPerRun : $maxItems;
        $mediaFiles = $this->getMediaFiles($limit);

        foreach($mediaFiles as $file) {
            $this->processFile($file);
        }

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function runOnFile(FileInterface $file, bool $andFlush = true): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->garbageCheckTimestamp = new DateTime();

        $this->processFile($file);

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    protected function getMediaFiles(?int $limit)
    {
        if ($limit <= 0) {
            $limit = null;
        }

        return $this->fileRepository->findBy(
            [],
            ['garbageCheckedAt' => 'ASC'],
            $limit
        );
    }

    protected function processFile(FileInterface $file): bool
    {
        $file->setGarbageCheckedAt($this->garbageCheckTimestamp);

        foreach($this->voters as $voter) {
            $result = $voter->vote($file);

            if ($result === GarbageCollectionVoterInterface::VOTE_DELETE) {
                $this->deleteFile($file);
                return true;
            }

            if ($result === GarbageCollectionVoterInterface::VOTE_KEEP) {
                return false;
            }
        }

        return false;
    }

    protected function deleteFile(FileInterface $file) {
        $this->entityManager->remove($file);
    }

    public function addVoter(GarbageCollectionVoterInterface $voter)
    {
        $this->voters[] = $voter;
    }
}
