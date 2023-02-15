<?php

namespace Enhavo\Bundle\MediaBundle\Command;

use Enhavo\Bundle\AppBundle\Util\StopWatch;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollectorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CollectGarbageCommand extends Command
{
    public function __construct(
        private GarbageCollectorInterface $garbageCollector,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:collect-garbage')
            ->setDescription('Run garbage collector for media files')
            ->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Override limit of items per run. 0 or negative values for unlimited.')
        ;

        if (method_exists($this->garbageCollector, 'dryRun')) {
            $this->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Dry run');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit');
        $dryRun = false;
        $dryRunText = '';
        if (method_exists($this->garbageCollector, 'dryRun')) {
            $dryRun = $input->getOption('dry-run');
            $dryRunText = ' in dry run mode';
        }

        $limitFlagText = '';
        if ($limit !== null) {
            $limit = intval($limit);
            if ($limit <= 0) {
                $limitFlagText = ' without limit';
            } else {
                $limitFlagText = ' with limit set to ' . $limit;
            }
        }
        $output->writeln('Starting garbage collection' . $limitFlagText . $dryRunText . '...');

        if (method_exists($this->garbageCollector, 'setLogOutput')) {
            $this->garbageCollector->setLogOutput($output);
        }

        $stopWatch = new StopWatch();
        $stopWatch->start();

        if ($dryRun) {
            $this->garbageCollector->dryRun($limit);
        } else {
            $this->garbageCollector->run($limit);
        }

        $stopWatchResults = $stopWatch->stop()->getResult();

        $output->writeln('finished, took ' . $stopWatchResults->getTimeReadable() . ', memory usage ' . $stopWatchResults->getMemoryReadable());

        return Command::SUCCESS;
    }
}
