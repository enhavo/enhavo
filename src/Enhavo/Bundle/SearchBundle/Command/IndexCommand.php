<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.05.18
 * Time: 11:59
 */

namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class IndexCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('enhavo:search:initialize')
            ->setDescription('Runs search initialize')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start initialize');

        $engineName = $this->container->getParameter('enhavo_search.search.engine');
        /** @var EngineInterface $engine */
        $engine = $this->container->get($engineName);
        $engine->initialize();

        $output->writeln('Initialize finished');

        return Command::SUCCESS;
    }
}
