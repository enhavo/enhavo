<?php

namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchSuggestCommand extends Command
{
    public function __construct(
        private SearchEngineInterface $searchEngine
    )
    {
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
        foreach ($results as $result)  {
            $output->writeln(sprintf('- %s', $result));
        }

        if (count($results) === 0) {
            $output->writeln('No result.');
        }
        
        return Command::SUCCESS;
    }
}
