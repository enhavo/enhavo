<?php

namespace Enhavo\Bundle\GridBundle\Command;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var bool
     */
    private $isDryRun;

    /**
     * @var bool
     */
    private $isDeleteOnMappingError;

    /**
     * CleanUpCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->isDryRun = false;
        $this->isDeleteOnMappingError = false;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:grid:clean-up')
            ->setDescription('Clean up orphaned grids and grid items')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
            ->addOption('mapping-error-delete', null, InputOption::VALUE_NONE, 'delete grid items with mapping errors')
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

        $output->writeln('Starting cleanup');
        if ($output->isVerbose()) $output->writeln('');

        if ($output->isVerbose()) $output->writeln('Deleting unreferenced grids');
        $deleted = $this->deleteUnreferencedGrids($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' grids deleted.');

        if ($output->isVerbose()) $output->writeln('Deleting grid item references without target entity');
        $deleted = $this->deleteGridItemsWithoutTargetEntity($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' grid item references deleted.');

        if ($output->isVerbose()) $output->writeln('');
        $output->writeln('Cleanup complete.');
        if ($this->isDryRun) $output->writeln('This was a dry run, no actual files were deleted.');

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    private function deleteUnreferencedGrids(OutputInterface $output)
    {
        $numDeleted = 0;

        $references = $this->getReferencesFor('grid_grid');
        $grids = $this->entityManager->getRepository(Grid::class)->findAll();

        /** @var Grid $grid */
        foreach($grids as $grid) {
            try {
                $isReferenced = $this->isReferenced($grid->getId(), $references);
            } catch (\Exception $exception) {
                throw new \Exception('Exception occurred while checking references for grid #' . $grid->getId() . ': ' . $exception->getMessage());
            }

            if (!$isReferenced) {
                if (!$this->isDryRun) $this->entityManager->remove($grid);
                if ($output->isVerbose()) $output->write('.');
                $numDeleted++;
            }
        }
        if ($numDeleted > 0) {
            if ($output->isVerbose()) $output->writeln('');
            $this->entityManager->flush();
        }

        return $numDeleted;
    }

    private function deleteGridItemsWithoutTargetEntity(OutputInterface $output)
    {
        $numDeleted = 0;
        $classRepositoryMappingExceptions = [];

        $gridItems = $this->entityManager->getRepository(Item::class)->findAll();
        foreach($gridItems as $gridItem) {
            if (isset($classRepositoryMappingExceptions[$gridItem->getItemTypeClass()])) {
                if ($this->isDeleteOnMappingError) {
                    $this->entityManager->remove($gridItem);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                }
                continue;
            }
            try {
                $targetEntityRepository = $this->entityManager->getRepository($gridItem->getItemTypeClass());
                $targetEntity = $targetEntityRepository->find($gridItem->getItemTypeId());
                if (!$targetEntity) {
                    $this->entityManager->remove($gridItem);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                }
            } catch (\Doctrine\Common\Persistence\Mapping\MappingException $exception) {
                $classRepositoryMappingExceptions[$gridItem->getItemTypeClass()] = $gridItem->getItemTypeClass();
                $output->write('');
                $output->write('MappingException trying to get Repository for class "' . $gridItem->getItemTypeClass() . '", ' . ($this->isDeleteOnMappingError ? 'deleting' : 'skipping'));
                if ($this->isDeleteOnMappingError) {
                    $output->write('');
                    $this->entityManager->remove($gridItem);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                } else {
                    $output->write('If you want grid items with mapping errors to be deleted, run again with option "--mapping-error-delete"');
                    $output->write('');
                }
            }
        }
        if ($numDeleted > 0) {
            if ($output->isVerbose()) $output->writeln('');
            $this->entityManager->flush();
        }

        return $numDeleted;
    }

    /**
     * @param string $referenceTargetTableName
     * @return Statement[]
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getReferencesFor($referenceTargetTableName)
    {
        $schema = $this->entityManager->getConnection()->getSchemaManager()->createSchema();
        $tables = $schema->getTables();

        $references = [];
        foreach($tables as $table) {
            foreach($table->getForeignKeys() as $foreignKeyConstraint) {
                if ($foreignKeyConstraint->getForeignTableName() == $referenceTargetTableName) {
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
                $referenceStatements []= $this->entityManager->getConnection()->prepare('SELECT count(*) AS nr FROM ' . $reference['table'] . ' WHERE ' . $column . ' = :id');
            }
        }

        return $referenceStatements;
    }

    /**
     * @param int $id
     * @param Statement[] $references
     * @return bool
     * @throws DBALException
     */
    private function isReferenced($id, $references)
    {
        foreach($references as $reference) {
            $reference->bindValue('id', $id);
            $reference->execute();
            $result = $reference->fetchAll();
            if ($result && count($result) > 0 && isset($result[0]['nr']) && $result[0]['nr'] > 0) {
                return true;
            }
        }
        return false;
    }
}
