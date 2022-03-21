<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
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
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $mediaPath;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var FormatRepository
     */
    private $formatRepository;

    /**
     * @var bool
     */
    private $isDryRun;

    /**
     * CleanUpCommand constructor.
     * @param Filesystem $fs
     * @param string $mediaPath
     * @param MediaManager $mediaManager
     * @param EntityManagerInterface $entityManager
     * @param FileRepository $fileRepository
     * @param FormatRepository $formatRepository
     */
    public function __construct(Filesystem $fs, string $mediaPath, MediaManager $mediaManager, EntityManagerInterface $entityManager, FileRepository $fileRepository, FormatRepository $formatRepository)
    {
        $this->fs = $fs;
        $this->mediaPath = $mediaPath;
        $this->mediaManager = $mediaManager;
        $this->entityManager = $entityManager;
        $this->fileRepository = $fileRepository;
        $this->formatRepository = $formatRepository;
        $this->isDryRun = false;
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
        $deleted = $this->deleteUnreferencedDatabaseEntries($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' database entries deleted.');

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
        $references = $this->getReferences();

        $numDeleted = 0;

        foreach($files as $file) {
            if ($file->isLibrary()) {
                continue;
            }
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
     * @throws \Doctrine\DBAL\DBALException
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
     * @throws DBALException
     */
    private function isReferenced($fileId, $references)
    {
        foreach($references as $reference) {
            $reference->bindValue('fileId', $fileId);
            $reference->execute();
            $result = $reference->fetchAll();
            if ($result && count($result) > 0 && isset($result[0]['nr']) && $result[0]['nr'] > 0) {
                return true;
            }
        }
        return false;
    }
}
