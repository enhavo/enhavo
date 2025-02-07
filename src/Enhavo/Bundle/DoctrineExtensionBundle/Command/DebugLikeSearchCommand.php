<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DebugLikeSearchCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:doctrine:like-search')
            ->setDescription('Perform sql LIKE search in all text fields of all entities')
            ->addArgument('searchTerm', InputArgument::REQUIRED, 'Search term in sql LIKE search syntax (use % as wildcard)')
            ->addOption('includeVarchar', null, InputOption::VALUE_NONE, 'Include VARCHAR fields, not only TEXT fields')
            ->addOption('excludeTables', null, InputOption::VALUE_REQUIRED, 'Comma separated list of table names to exclude')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchTerm = $input->getArgument('searchTerm');
        $includeVarchar = $input->getOption('includeVarchar', false);

        $excludeTables = $input->getOption('excludeTables', '');
        if ($excludeTables) {
            $excludeTables = explode(',', $excludeTables);
            foreach ($excludeTables as $index => $tableName) {
                $excludeTables[$index] = trim($tableName);
            }
        } else {
            $excludeTables = [];
        }

        $fieldsToSearch = $this->gatherFieldsToSearch($includeVarchar, $excludeTables);
        $this->search($fieldsToSearch, $searchTerm, $output);

        $output->writeln('');
        $output->writeln('Finished.');

        return Command::SUCCESS;
    }

    private function gatherFieldsToSearch($includeString = true, $excludeTables = [])
    {
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $result = [];

        foreach($metaData as $classMetaData) {
            if (!$classMetaData->table || !isset($classMetaData->table['name'])) {
                continue;
            }
            if (!$classMetaData->identifier || count($classMetaData->identifier) !== 1 || $classMetaData->identifier[0] !== 'id') {
                continue;
            }
            if ($classMetaData->isMappedSuperclass) {
                continue;
            }

            $tableName = $classMetaData->table['name'];
            if(in_array($tableName, $excludeTables)) {
                continue;
            }

            $columns = [];

            foreach($classMetaData->getFieldNames() as $fieldName) {
                $fieldMapping = $classMetaData->getFieldMapping($fieldName);
                if ($fieldMapping['type'] === 'text') {
                    $columns[$fieldName] = $fieldName;
                }
                if ($fieldMapping['type'] === 'string' && $includeString) {
                    $columns[$fieldName] = $fieldName;
                }
            }

            if (count($columns) > 0) {
                if (!isset($result[$tableName])) {
                    $result[$tableName] = [];
                }
                foreach($columns as $column) {
                    $result[$tableName][$column] = $column;
                }
            }
        }

        return $result;
    }

    private function search(array $fieldsToSearch, string $searchTerm, OutputInterface $output)
    {
        $connection = $this->entityManager->getConnection();
        $lastWasHit = true;

        foreach($fieldsToSearch as $tableName => $fieldNames) {
            $fieldQueries = [];
            foreach($fieldNames as $fieldName) {
                $fieldQueries []= sprintf('%s LIKE "%s"', $fieldName, $searchTerm);
            }
            $query = sprintf('SELECT id FROM %s WHERE %s', $tableName, implode(' OR ', $fieldQueries));

            $result = $connection->executeQuery($query)->fetchAllAssociative();

            if (count($result) > 0) {
                if (!$lastWasHit) {
                    $output->writeln('');
                }
                $lastWasHit = true;
                foreach($result as $row) {
                    $output->writeln(sprintf('Found: %s #%s', $tableName, $row['id']));
                }
            } else {
                $output->write('.');
                $lastWasHit = false;
            }
        }
    }
}
