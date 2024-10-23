<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\ResourceBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class DebugResourceCommand extends Command
{
    public function __construct(
        private readonly array $resources
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:resource')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the resource')
            ->setDescription('Show all resources')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        foreach ($this->resources as $key => $resource) {
            if (str_starts_with($key, $name)) {
                $output->writeln('------------------------------------------------------------');
                $output->writeln(sprintf("Name: <info>%s</info>", $key));
                $output->writeln('------------------------------------------------------------');
                $lines = explode("\n", Yaml::dump($resource, 4));
                foreach ($lines as $line) {
                    $output->writeln('  ' . $line);
                }
            }
        }

        return Command::SUCCESS;
    }
}
