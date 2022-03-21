<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/*
 * This command does the reindexing
 */
class ReindexCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('enhavo:search:reindex')
            ->setDescription('Runs search reindex')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start reindexing');

        $engineName = $this->container->getParameter('enhavo_search.search.engine');
        /** @var EngineInterface $engine */
        $engine = $this->container->get($engineName);
        $engine->reindex();

        $output->writeln('Indexing finished');

        return Command::SUCCESS;
    }
}
