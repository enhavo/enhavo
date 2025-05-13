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

use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * This command does the reindexing
 */
class SearchCommand extends Command
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
        private ResultConverter $resultConverter,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search')
            ->setDescription('Search')
            ->addArgument('term')
            ->addOption('fuzzy', 'f')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $term = $input->getArgument('term');
        $fuzzy = $input->getOption('fuzzy');

        $filter = new Filter();
        $filter->setTerm($term);
        $filter->setFuzzy($fuzzy);
        $filter->setLimit(10);
        $summary = $this->searchEngine->search($filter);
        $results = $this->resultConverter->convert($summary, $term);

        $output->writeln(sprintf('Search: %s', $term));
        foreach ($results as $result) {
            $output->writeln('----------------');
            $output->writeln(sprintf('Title: %s', $result->getTitle()));
            $output->writeln(sprintf('Text: %s', $result->getText()));
            $output->writeln(sprintf('Class: %s', get_class($result->getSubject())));
        }

        if (0 === count($results)) {
            $output->writeln('----------------');
            $output->writeln('No result.');
        }

        $output->writeln('----------------');

        return Command::SUCCESS;
    }
}
