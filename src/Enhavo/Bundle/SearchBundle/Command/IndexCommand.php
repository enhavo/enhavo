<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:search:index')
            ->setDescription('Runs search indexing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexEngine = $this->getContainer()->get('enhavo_search_index_engine');
        $indexEngine->reindex();

        $output->writeln('Indexing finished');
    }
}