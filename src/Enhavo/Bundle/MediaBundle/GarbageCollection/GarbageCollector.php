<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\GarbageCollection;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\GarbageCollection\Voter\GarbageCollectionVoterInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Symfony\Component\Console\Output\OutputInterface;

class GarbageCollector implements GarbageCollectorInterface
{
    /** @var GarbageCollectionVoterInterface[] */
    private array $voters = [];

    private ?\DateTime $garbageCheckTimestamp = null;

    private ?OutputInterface $logOutput = null;

    private bool $dryRun = false;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityRepository $fileRepository,
        protected bool $enabled,
        protected int $maxItemsPerRun,
    ) {
    }

    public function run(?int $maxItems = null, bool $andFlush = true): void
    {
        $this->dryRun = false;
        $this->doRun($maxItems, $andFlush);
    }

    public function dryRun(?int $maxItems = null, bool $andFlush = true): void
    {
        $this->dryRun = true;
        $this->doRun($maxItems, $andFlush);
    }

    private function doRun(?int $maxItems = null, bool $andFlush = true): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->garbageCheckTimestamp = new \DateTime();

        $limit = null === $maxItems ? $this->maxItemsPerRun : $maxItems;
        $mediaFiles = $this->getMediaFiles($limit);

        foreach ($mediaFiles as $file) {
            $this->processFile($file);
        }

        if ($andFlush && !$this->dryRun) {
            $this->entityManager->flush();
        }
    }

    public function runOnFile(FileInterface $file, bool $andFlush = true): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->garbageCheckTimestamp = new \DateTime();

        $this->processFile($file);

        if ($andFlush && !$this->dryRun) {
            $this->entityManager->flush();
        }
    }

    public function setLogOutput(OutputInterface $logOutput)
    {
        $this->logOutput = $logOutput;
    }

    private function getMediaFiles(?int $limit)
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

    private function processFile(FileInterface $file): bool
    {
        if (!$this->dryRun) {
            $file->setGarbageCheckedAt($this->garbageCheckTimestamp);
        }

        foreach ($this->voters as $voter) {
            $result = $voter->vote($file);

            if (GarbageCollectionVoterInterface::VOTE_DELETE === $result) {
                if ($this->dryRun) {
                    $this->log('Dry Run: '.$file->getId().' to be deleted, voted by '.get_class($voter));
                } else {
                    $this->deleteFile($file);
                }

                return true;
            }

            if (GarbageCollectionVoterInterface::VOTE_KEEP === $result) {
                return false;
            }
        }

        return false;
    }

    private function deleteFile(FileInterface $file)
    {
        $this->entityManager->remove($file);
    }

    private function log(string $message)
    {
        if ($this->logOutput && $this->logOutput->isVerbose()) {
            $this->logOutput->writeln($message);
        }
    }

    public function addVoter(GarbageCollectionVoterInterface $voter)
    {
        $this->voters[] = $voter;
    }
}
