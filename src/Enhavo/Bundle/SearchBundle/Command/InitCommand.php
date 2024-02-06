<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * This command does the reindexing
 */
class InitCommand extends Command
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
            ->setName('enhavo:search:init')
            ->setDescription('Runs search init')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start init');
        $this->searchEngine->initialize();
        return Command::SUCCESS;
    }
}
