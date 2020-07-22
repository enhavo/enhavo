<?php

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Logger;

abstract class AbstractOutputLogger implements OutputLoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = array())
    {
        $this->writeln($message, Logger::EMERGENCY, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = array())
    {
        $this->writeln($message, Logger::ALERT, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = array())
    {
        $this->writeln($message, Logger::CRITICAL, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = array())
    {
        $this->writeln($message, Logger::ERROR, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = array())
    {
        $this->writeln($message, Logger::WARNING, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = array())
    {
        $this->writeln($message, Logger::NOTICE, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = array())
    {
        $this->writeln($message, Logger::INFO, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = array())
    {
        $this->writeln($message, Logger::DEBUG, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        $level = Logger::toMonologLevel($level);
        $this->writeln($message, $level, $context);
    }
}
