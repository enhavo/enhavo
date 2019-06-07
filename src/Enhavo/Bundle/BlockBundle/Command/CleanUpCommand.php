<?php

namespace Enhavo\Bundle\BlockBundle\Command;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\BlockBundle\Entity\Container;
use Enhavo\Bundle\BlockBundle\Entity\Block;
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
            ->setName('enhavo:block:clean-up')
            ->setDescription('Clean up orphaned containers and container blocks')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
            ->addOption('mapping-error-delete', null, InputOption::VALUE_NONE, 'delete container blocks with mapping errors')
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

        if ($output->isVerbose()) $output->writeln('Deleting unreferenced containers');
        $deleted = $this->deleteUnreferencedContainers($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' containers deleted.');

        if ($output->isVerbose()) $output->writeln('Deleting container block references without target entity');
        $deleted = $this->deleteContainerBlockWithoutTargetEntity($output);
        if ($output->isVerbose()) $output->writeln('done, ' . $deleted . ' container block references deleted.');

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
    private function deleteUnreferencedContainers(OutputInterface $output)
    {
        $numDeleted = 0;

        $references = $this->getReferencesFor('block_container');
        $containers = $this->entityManager->getRepository(Container::class)->findAll();

        /** @var Container $container */
        foreach($containers as $container) {
            try {
                $isReferenced = $this->isReferenced($container->getId(), $references);
            } catch (\Exception $exception) {
                throw new \Exception('Exception occurred while checking references for container #' . $container->getId() . ': ' . $exception->getMessage());
            }

            if (!$isReferenced) {
                if (!$this->isDryRun) $this->entityManager->remove($container);
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

    private function deleteContainerBlockWithoutTargetEntity(OutputInterface $output)
    {
        $numDeleted = 0;
        $classRepositoryMappingExceptions = [];

        $containerBlocks = $this->entityManager->getRepository(Block::class)->findAll();
        foreach($containerBlocks as $containerBlock) {
            if (isset($classRepositoryMappingExceptions[$containerBlock->getBlockTypeClass()])) {
                if ($this->isDeleteOnMappingError) {
                    $this->entityManager->remove($containerBlock);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                }
                continue;
            }
            try {
                $targetEntityRepository = $this->entityManager->getRepository($containerBlock->getBlockTypeClass());
                $targetEntity = $targetEntityRepository->find($containerBlock->getBlockTypeId());
                if (!$targetEntity) {
                    $this->entityManager->remove($containerBlock);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                }
            } catch (\Doctrine\Common\Persistence\Mapping\MappingException $exception) {
                $classRepositoryMappingExceptions[$containerBlock->getBlockTypeClass()] = $containerBlock->getBlockTypeClass();
                $output->write('');
                $output->write('MappingException trying to get Repository for class "' . $containerBlock->getBlockTypeClass() . '", ' . ($this->isDeleteOnMappingError ? 'deleting' : 'skipping'));
                if ($this->isDeleteOnMappingError) {
                    $output->write('');
                    $this->entityManager->remove($containerBlock);
                    if ($output->isVerbose()) $output->write('.');
                    $numDeleted++;
                } else {
                    $output->write('If you want container blocks with mapping errors to be deleted, run again with option "--mapping-error-delete"');
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
