<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\FrameworkBundle\Output\CliOutputLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanUpCommand extends Command
{
    /**
     * CleanUpCommand constructor.
     */
    public function __construct(
        private BlockManager $blockManager,
        private EntityManagerInterface $entityManager,
    ) {
        $this->blockManager = $blockManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('enhavo:block:clean-up')
            ->setDescription('Clean up orphaned containers and container blocks')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isDryRun = $input->getOption('dry-run');

        // TODO: this is a workaround. in the future we need an interface to take care about the activated filters so that every such command can take care about which filters can stay active and which not
        $filters = $this->entityManager->getFilters();
        if ($filters->isEnabled('revision')) {
            $filters->disable('revision');
        }

        $this->blockManager->cleanUp(new CliOutputLogger(new SymfonyStyle($input, $output)), $isDryRun);

        if ($isDryRun) {
            $output->writeln('This was a dry run, no actual changes were made.');
        }

        return Command::SUCCESS;
    }
}
