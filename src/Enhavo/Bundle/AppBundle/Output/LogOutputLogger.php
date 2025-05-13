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
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function writeln($message, $level = Level::Info, $context = [])
    {
        if ('' !== $this->lineBuffer) {
            if ($this->lineBufferLogLevel !== $level) {
                $this->flushLineBuffer();
            } else {
                $message = $this->lineBuffer.$message;
                $this->lineBuffer = '';
            }
        }
        $this->logger->log($level, $message, $context);
    }

    public function write($message, $level = Level::Info)
    {
        if ('' !== $this->lineBuffer && $this->lineBufferLogLevel != $level) {
            $this->flushLineBuffer();
        }
        $this->lineBuffer .= $message;
        $this->lineBufferLogLevel = $level;
    }

    public function progressStart($max = 0)
    {
        // No progress bar in log
    }

    public function progressAdvance($step = 1)
    {
        // No progress bar in log
    }

    public function progressFinish()
    {
        // No progress bar in log
    }

    private function flushLineBuffer()
    {
        if ('' !== $this->lineBuffer) {
            $this->logger->log($this->lineBufferLogLevel, $this->lineBuffer, []);
        }
        $this->lineBuffer = '';
    }
}
