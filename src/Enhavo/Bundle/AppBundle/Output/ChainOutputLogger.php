<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Level;

class ChainOutputLogger implements OutputLoggerInterface
{
    /**
     * @var OutputLoggerInterface[]
     */
    private $outputLoggers;

    public function addOutputLogger(OutputLoggerInterface $outputLogger)
    {
        $this->outputLoggers[] = $outputLogger;
    }

    public function writeln($message, $level = Level::Info, $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->writeln($message, $level, $context);
        }
    }

    public function write($message, $level = Level::Info)
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->write($message, $level);
        }
    }

    public function progressStart($max = 0)
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->progressStart($max);
        }
    }

    public function progressAdvance($step = 1)
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->progressAdvance($step);
        }
    }

    public function progressFinish()
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->progressFinish();
        }
    }

    public function emergency($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->emergency($message, $context);
        }
    }

    public function alert($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->alert($message, $context);
        }
    }

    public function critical($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->critical($message, $context);
        }
    }

    public function error($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->error($message, $context);
        }
    }

    public function warning($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->warning($message, $context);
        }
    }

    public function notice($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->notice($message, $context);
        }
    }

    public function info($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->info($message, $context);
        }
    }

    public function debug($message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->debug($message, $context);
        }
    }

    public function log($level, $message, array $context = [])
    {
        foreach ($this->outputLoggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
