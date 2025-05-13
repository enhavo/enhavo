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

use Enhavo\Bundle\AppBundle\Output\CliOutputLogger;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/*
 * This command does the reindexing
 */
class IndexCommand extends Command
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search:index')
            ->addArgument('class', InputArgument::OPTIONAL, 'Class to index')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Ignore errors')
            ->setDescription('Runs search (re)index')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        $force = (bool) $input->getOption('force');

        $output->writeln('Start reindexing');
        $logger = new CliOutputLogger(new SymfonyStyle($input, $output));
        $this->searchEngine->reindex($force, $class, $logger);
        $output->writeln('Indexing finished');

        return Command::SUCCESS;
    }
}
