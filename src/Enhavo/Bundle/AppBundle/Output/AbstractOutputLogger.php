<?php

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Level;
use Monolog\Logger;

abstract class AbstractOutputLogger implements OutputLoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function emergency(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Emergency, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Alert, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Critical, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Error, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Warning, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Notice, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Info, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug(string|\Stringable $message, array $context = array()): void
    {
        $this->writeln($message, Level::Debug, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = array()): void
    {
        $level = Logger::toMonologLevel($level);
        $this->writeln($message, $level, $context);
    }
}
