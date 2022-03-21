<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 21.04.17
 * Time: 18:27
 */

namespace Enhavo\Bundle\CalendarBundle\Command;

use Enhavo\Bundle\CalendarBundle\Import\ImportManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var ImportManager
     */
    private $manager;

    /**
     * ImportCommand constructor.
     * @param ImportManager $manager
     */
    public function __construct(ImportManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:calendar:import')
            ->setDescription('import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager->import();
        return Command::SUCCESS;
    }
}
