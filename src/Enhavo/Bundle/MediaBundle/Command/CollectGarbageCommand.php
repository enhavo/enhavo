<?php

namespace Enhavo\Bundle\MediaBundle\Command;

use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollectorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

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
            if ($dryRun) {
                $dryRunText = ' in dry run mode';
            }
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

        $stopWatch = new Stopwatch();
        $stopWatch->start('mediaGarbageCollect');

        if ($dryRun) {
            $this->garbageCollector->dryRun($limit);
        } else {
            $this->garbageCollector->run($limit);
        }

        $stopWatchEvent = $stopWatch->stop('mediaGarbageCollect');

        $output->writeln('finished, took ' . $this->formatTimeReadable($stopWatchEvent->getDuration()) . ', memory usage ' . $this->formatMemoryReadable($stopWatchEvent->getMemory()));

        return Command::SUCCESS;
    }

    private function formatTimeReadable(float $time)
    {
        $time = round($time / 1000);
        $timeMinutes = (int)floor($time / 60);
        $timeSeconds = (int)($time % 60);
        if ($timeMinutes > 0) {
            return sprintf('%sm%ss', $timeMinutes, $timeSeconds);
        }
        return $timeSeconds . 's';
    }

    private function formatMemoryReadable(int $memory)
    {
        if ($memory < 1024) {
            return $memory . 'b';
        } elseif ($memory < 1048576) {
            return round($memory / 1024) . 'kb';
        } else {
            return round($memory / 1048576) . 'mb';
        }
    }
}
