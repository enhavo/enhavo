<?php

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Level;

class ChainOutputLogger implements OutputLoggerInterface
{
    /**
     * @var OutputLoggerInterface[]
     */
    private $outputLoggers;

    /**
     * @param OutputLoggerInterface $outputLogger
     */
    public function addOutputLogger(OutputLoggerInterface $outputLogger)
    {
        $this->outputLoggers []= $outputLogger;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($message, $level = Level::Info, $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->writeln($message, $level, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($message, $level = Level::Info)
    {
        foreach($this->outputLoggers as $logger) {
            $logger->write($message, $level);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function progressStart($max = 0)
    {
        foreach($this->outputLoggers as $logger) {
            $logger->progressStart($max);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function progressAdvance($step = 1)
    {
        foreach($this->outputLoggers as $logger) {
            $logger->progressAdvance($step);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function progressFinish()
    {
        foreach($this->outputLoggers as $logger) {
            $logger->progressFinish();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->emergency($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->alert($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->critical($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->error($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->warning($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->notice($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->info($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->debug($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        foreach($this->outputLoggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
