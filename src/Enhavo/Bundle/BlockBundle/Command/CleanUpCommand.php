<?php

namespace Enhavo\Bundle\BlockBundle\Command;

use Enhavo\Bundle\AppBundle\Output\CliOutputLogger;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanUpCommand extends Command
{
    /**
     * @var BlockManager
     */
    private $blockManager;

    /**
     * CleanUpCommand constructor.
     * @param BlockManager $blockManager
     */
    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
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

        $this->blockManager->cleanUp(new CliOutputLogger(new SymfonyStyle($input, $output)), $isDryRun);

        if ($isDryRun) $output->writeln('This was a dry run, no actual changes were made.');

        return 0;
    }
}
