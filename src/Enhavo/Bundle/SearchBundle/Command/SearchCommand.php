<?php
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
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search')
            ->setDescription('Search')
            ->addArgument('term')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $term = $input->getArgument('term');

        $filter = new Filter();
        $filter->setTerm($term);
        $filter->setLimit(10);
        $results = $this->searchEngine->search($filter);
        $results = $this->resultConverter->convert($results, $term);

        $output->writeln(sprintf('Search: %s', $term));
        foreach ($results as $result)  {
            $output->writeln(sprintf('Title: %s', $result->getTitle()));
            $output->writeln(sprintf('Text: %s', $result->getText()));
            $output->writeln(sprintf('Class: %s', get_class($result->getSubject())));
        }

        return Command::SUCCESS;
    }
}
