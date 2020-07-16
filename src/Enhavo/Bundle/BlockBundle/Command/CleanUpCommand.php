<?php

namespace Enhavo\Bundle\BlockBundle\Command;

use Enhavo\Bundle\BlockBundle\CleanUp\CleanUpManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpCommand extends Command
{
    /**
     * @var CleanUpManager
     */
    private $cleanUpManager;

    /**
     * CleanUpCommand constructor.
     * @param CleanUpManager $cleanUpManager
     */
    public function __construct(CleanUpManager $cleanUpManager)
    {
        $this->cleanUpManager = $cleanUpManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:block:clean-up')
            ->setDescription('Clean up orphaned containers and container blocks')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isDryRun = $input->getOption('dry-run');

        $output->writeln('Starting cleanup');
        $this->cleanUpManager->cleanUp($output, $isDryRun);
        $output->writeln('Cleanup complete, ' . $this->cleanUpManager->getNodesDeleted() . ' nodes and ' . $this->cleanUpManager->getBlocksDeleted() . ' blocks deleted.');

        if ($isDryRun) $output->writeln('This was a dry run, no actual files were deleted.');

        return 0;
    }

}
