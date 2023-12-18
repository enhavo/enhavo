<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\AppBundle\Command;


use Enhavo\Bundle\AppBundle\Endpoint\Template\TemplateEndpointCollector;
use Enhavo\Bundle\AppBundle\Endpoint\Template\TemplateEndpointFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DebugEndpointTemplate extends Command
{
    public function __construct(
        private TemplateEndpointCollector $collector
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:endpoint:template')
            ->setDescription('Show all template endpoints')
            ->addArgument('search', InputArgument::OPTIONAL, 'Fulltext search')
            ->addOption('template', null, InputOption::VALUE_REQUIRED, 'Template name')
            ->addOption('path', null, InputOption::VALUE_REQUIRED,  'Route path')
            ->addOption('route', null, InputOption::VALUE_REQUIRED, 'Route name')
            ->addOption('description', null, InputOption::VALUE_REQUIRED, 'Description')
            ->addOption('list', null, InputOption::VALUE_NONE, 'Show as list')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filter = new TemplateEndpointFilter(
            $input->getArgument('search'),
            $input->getOption('template'),
            $input->getOption('path'),
            $input->getOption('route'),
            $input->getOption('description'),
        );

        $output->writeln('Show all template routes');

        if ($input->getOption('list')) {
            $this->showList($output, $filter);
        } else {
            $this->showTable($output, $filter);
        }

        return Command::SUCCESS;
    }

    private function showTable(OutputInterface $output, TemplateEndpointFilter $filter): void
    {
        $entries = $this->collector->collect($filter);

        $table = new Table($output);
        $table->setHeaders(['Template', 'Path', 'Route', 'Description']);

        foreach ($entries as $entry) {
            $table->addRow([$entry->getTemplate(), $entry->getPath(), $entry->getRouteName(), $entry->getDescription()]);
        }

        $table->render();
    }

    private function showList(OutputInterface $output, TemplateEndpointFilter $filter): void
    {

        $entries = $this->collector->collect($filter);

        foreach ($entries as $entry) {
            $output->writeln('----------------------');
            $output->writeln(sprintf('Template: %s', $entry->getTemplate()));
            $output->writeln(sprintf('Path: %s', $entry->getPath()));
            $output->writeln(sprintf('Route: %s', $entry->getRouteName()));
            $output->writeln(sprintf('Description: %s', $entry->getDescription()));
        }

        $output->writeln('----------------------');
    }
}
