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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchSuggestCommand extends Command
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search:suggest')
            ->setDescription('Search suggestion')
            ->addArgument('term')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $term = $input->getArgument('term');

        $filter = new Filter();
        $filter->setTerm($term);
        $results = $this->searchEngine->suggest($filter);

        $output->writeln(sprintf('Search: %s', $term));
        foreach ($results as $result) {
            $output->writeln(sprintf('- %s', $result));
        }

        if (0 === count($results)) {
            $output->writeln('No result.');
        }

        return Command::SUCCESS;
    }
}
