<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataProvider;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * This command does the reindexing
 */
#[AsCommand(
    name: 'debug:search:analyze',
    description: 'Check index metadata',
)]
class AnalyzeCommand extends Command
{
    public function __construct(
        private IndexDataProvider $indexDataProvider,
        private FilterDataProvider $filterDataProvider,
        private EntityManagerInterface $em,
        private ResourceManager $resourceManager,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'FQCN or resource name')
            ->addArgument('id', InputArgument::REQUIRED, 'id of the entity')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityName = $input->getArgument('entity');
        $id = $input->getArgument('id');

        $entity = null;
        if (class_exists($entityName)) {
            $repository = $this->em->getRepository($entityName);
            $entity = $repository->find($id);
        } elseif ($this->resourceManager->getMetadata($entityName)) {
            $repository = $this->resourceManager->getRepository($entityName);
            $entity = $repository->find($id);
        } else {
            $output->writeln('<error>Entity "'.$entityName.'" is not a FCQN nor a valid resource name</error>');
            return Command::FAILURE;
        }

        if ($entity === null) {
            $output->writeln('<error>Entity with id "'.$id.'" not found</error>');
            return Command::FAILURE;
        }

        if (null === $entity) {
            $output->writeln('Entity not found');
            return Command::FAILURE;
        }

        $data = $this->indexDataProvider->getIndexData($entity);
        if (0 === count($data)) {
            $output->writeln('No data to index');
        } else {
            $output->writeln('Data:');
            foreach ($data as $indexData) {
                $output->writeln(sprintf('%s: %s', $indexData->getWeight(), $indexData->getValue()));
            }
        }

        $filter = $this->filterDataProvider->getFilterData($entity);
        if (0 === count($filter)) {
            $output->writeln('No filter to index');
        } else {
            $output->writeln('Filter:');
            foreach ($filter as $filterData) {
                $output->writeln(sprintf('%s: %s', $filterData->getKey(), $filterData->getValue()));
            }
        }

        return Command::SUCCESS;
    }
}
