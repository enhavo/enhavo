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
        $container = $this->getApplication()->getKernel()->getContainer();
        $engine = $container->getParameter('enhavo_search.search.index_engine');
        $indexEngine = $this->getContainer()->get($engine);
        $indexEngine->reindex();

        $output->writeln('Indexing finished');
    }
}