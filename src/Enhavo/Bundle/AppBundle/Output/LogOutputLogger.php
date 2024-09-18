<?php

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Level;
use Psr\Log\LoggerInterface;

class LogOutputLogger extends AbstractOutputLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $lineBuffer = '';

    /**
     * @var int
     */
    private $lineBufferLogLevel;

    /**
     * TerminalOutputLogger constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($message, $level = Level::Info, $context = array())
    {
        if ($this->lineBuffer !== '') {
            if ($this->lineBufferLogLevel !== $level) {
                $this->flushLineBuffer();
            } else {
                $message = $this->lineBuffer . $message;
                $this->lineBuffer = '';
            }
        }
        $this->logger->log($level, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function write($message, $level = Level::Info)
    {
        if ($this->lineBuffer !== '' && $this->lineBufferLogLevel != $level) {
            $this->flushLineBuffer();
        }
        $this->lineBuffer .= $message;
        $this->lineBufferLogLevel = $level;
    }

    /**
     * {@inheritdoc}
     */
    public function progressStart($max = 0)
    {
        // No progress bar in log
    }

    /**
     * {@inheritdoc}
     */
    public function progressAdvance($step = 1)
    {
        // No progress bar in log
    }

    /**
     * {@inheritdoc}
     */
    public function progressFinish()
    {
        // No progress bar in log
    }

    private function flushLineBuffer()
    {
        if ($this->lineBuffer !== '') {
            $this->logger->log($this->lineBufferLogLevel, $this->lineBuffer, array());
        }
        $this->lineBuffer = '';
    }
}
