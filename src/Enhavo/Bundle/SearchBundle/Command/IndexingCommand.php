<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class IndexingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('indexing:run')
            ->setDescription('Runs search indexing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Index engine über container holen
        //get all datasets to update
        $indexEngine = $this->getContainer()->get('enhavo_search_engine');
        $indexEngine->indexAll();

        $output->writeln('Indexing finished');
    }
}