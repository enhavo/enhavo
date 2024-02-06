<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * This command does the reindexing
 */
class IndexCommand extends Command
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search:index')
            ->setDescription('Runs search (re)index')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start reindexing');
        $this->searchEngine->reindex();
        $output->writeln('Indexing finished');
        return Command::SUCCESS;
    }
}
