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
use Enhavo\Bundle\SearchBundle\Filter\FilterDataProvider;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * This command does the reindexing
 */
class AnalyzeCommand extends Command
{
    public function __construct(
        private IndexDataProvider $indexDataProvider,
        private FilterDataProvider $filterDataProvider,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:search:analyze')
            ->setDescription('Check index metadata')
            ->addArgument('class')
            ->addArgument('id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        $id = $input->getArgument('id');

        $entity = $this->em->getRepository($class)->find($id);

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
