<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 21.04.17
 * Time: 18:27
 */

namespace Enhavo\Bundle\CalendarBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ImportCommand extends ContainerAwareCommand
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('enhavo:calendar:import')
            ->setDescription('import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getContainer()->get('enhavo_calendar.import_manager');
        $importer->import();
    }
}