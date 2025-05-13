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
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search:init')
            ->setDescription('Runs search init')
            ->addOption('force', 'f')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('force');

        if ($force) {
            $output->writeln('Start init with force. Index will be deleted');
        } else {
            $output->writeln('Start init');
        }

        $this->searchEngine->initialize($force);
        $output->writeln('Init finish');

        return Command::SUCCESS;
    }
}
