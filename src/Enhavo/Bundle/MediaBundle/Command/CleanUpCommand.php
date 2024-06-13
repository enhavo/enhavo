<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaBundle\Repository\FormatRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CleanUpCommand extends Command
{
    /**
     * @var bool
     */
    private $isDryRun = false;

    public function __construct(
        private Filesystem $fs,
        private string $mediaPath,
        private MediaManager $mediaManager,
        private EntityManagerInterface $entityManager,
        private FileRepository $fileRepository,
        private FormatRepository $formatRepository,
        private bool $enableDeleteUnreferenced,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:clean-up')
            ->setDescription('Clean up unused media files')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isDryRun = $input->getOption('dry-run');

        $output->writeln('Starting file cleanup');
        if ($output->isVerbose()) $output->writeln('');

        if ($output->isVerbose()) $output->writeln('Deleting unreferenced file database entries...');
        if ($this->enableDeleteUnreferenced) {
            $deleted = $this->deleteUnreferencedDatabaseEntries($output);
            if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' database entries deleted.');

        } else {
            if ($output->isVerbose()) $output->writeln('skipped, enable_delete_unreferenced is disabled.');
        }

        if ($output->isVerbose()) $output->writeln('Deleting files in media directory without database entry...');
        $deleted = $this->deleteFilesWithoutDatabaseEntry($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' files deleted.');

        if ($output->isVerbose()) $output->writeln('');
        $output->writeln('Cleanup complete.');
        if ($this->isDryRun) $output->writeln('This was a dry run, no actual files were deleted.');

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    private function deleteUnreferencedDatabaseEntries(OutputInterface $output)
    {
        $files = $this->mediaManager->findBy([]);
        $references = $this->getReferences(); // todo: use doctrine extension bundle

        $numDeleted = 0;

        foreach($files as $file) {

            try {
                $isReferenced = $this->isReferenced($file->getId(), $references);
            } catch (\Exception $exception) {
                throw new \Exception('Exception occurred while checking references for file #' . $file->getId() . ': ' . $exception->getMessage());
            }

            if (!$isReferenced) {
                if (!$this->isDryRun) $this->mediaManager->deleteFile($file);
                if ($output->isVerbose()) $output->write('.');
                $numDeleted++;
            }
        }
        if ($numDeleted > 0) {
            if ($output->isVerbose()) $output->writeln('');
        }

        return $numDeleted;
    }

    /**
     * @param OutputInterface $output
     * @return int
     */
    private function deleteFilesWithoutDatabaseEntry(OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in($this->mediaPath);

        $toDelete = [];

        foreach($finder as $fileInfo) {
            if(preg_match('/^\d+$/', $fileInfo->getFilename())) {
                /** @var FileInterface|null $file */
                $file = $this->fileRepository->find($fileInfo->getFilename());
                if (!$file) {
                    $toDelete [] = $fileInfo->getPath() . '/' . $fileInfo->getFilename();
                    if ($output->isVerbose()) $output->write('.');
                } else {
                    if ($this->mediaPath !== $fileInfo->getPath()) {
                        $subPath = substr($fileInfo->getPath(), strlen($this->mediaPath) + 1);
                        $format = $this->formatRepository->findOneBy(['name' => $subPath, 'file' => $file]);
                        if (!$format) {
                            $toDelete [] = $fileInfo->getPath() . '/' . $fileInfo->getFilename();
                            if ($output->isVerbose()) $output->write('.');
                        }
                    }
                }
            }
        }

        if (!$this->isDryRun) {
            foreach($toDelete as $filePath) {
                $this->fs->remove($filePath);
            }
        }

        if (count($toDelete) > 0) {
            if ($output->isVerbose()) $output->writeln('');
        }

        return count($toDelete);
    }

    /**
     * @return Statement[]
     */
    private function getReferences()
    {
        $schema = $this->entityManager->getConnection()->getSchemaManager()->createSchema();
        $tables = $schema->getTables();

        $references = [];
        foreach($tables as $table) {
            if ($table->getName() == 'media_format') {
                continue;
            }
            foreach($table->getForeignKeys() as $foreignKeyConstraint) {
                if ($foreignKeyConstraint->getForeignTableName() == 'media_file') {
                    $references []= [
                        'table' => $table->getName(),
                        'columns' => $foreignKeyConstraint->getLocalColumns()
                    ];
                }
            }
        }

        $referenceStatements = [];
        foreach($references as $reference) {
            foreach ($reference['columns'] as $column) {
                $referenceStatements []= $this->entityManager->getConnection()->prepare('SELECT count(*) AS nr FROM ' . $reference['table'] . ' WHERE ' . $column . ' = :fileId');
            }
        }

        return $referenceStatements;
    }

    /**
     * @param int $fileId
     * @param Statement[] $references
     * @return bool
     */
    private function isReferenced($fileId, $references)
    {
        foreach($references as $reference) {
            $reference->bindValue('fileId', $fileId);
            $result = $reference->executeQuery();
            if ($result && $result->rowCount() > 0) {
                $row = $result->fetchAllAssociative();
                if (isset($row[0]['nr']) && $row[0]['nr'] > 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
