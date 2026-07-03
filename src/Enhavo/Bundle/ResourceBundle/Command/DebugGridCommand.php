<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'debug:grid',
    description: 'Show all grids',
)]
class DebugGridCommand extends Command
{
    public function __construct(
        private $grids,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the grid')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        foreach ($this->grids as $key => $grid) {
            if (str_starts_with($key, $name)) {
                $output->writeln('------------------------------------------------------------');
                $output->writeln(sprintf('Name: <info>%s</info>', $key));
                $output->writeln('------------------------------------------------------------');
                $lines = explode("\n", Yaml::dump($grid, 4));
                foreach ($lines as $line) {
                    $output->writeln('  '.$line);
                }
            }
        }

        return Command::SUCCESS;
    }
}
