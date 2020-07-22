<?php

namespace Enhavo\Bundle\AppBundle\Output;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

interface OutputLoggerInterface extends LoggerInterface
{
    /**
     * Write a line of text to the output finished by a newline
     *
     * @param string $message The message to write
     * @param int $level The log level used, one of the constants from Monolog\Logger
     * @param array $context The log context as used in Monolog\LoggerInterface functions
     */
    public function writeln($message, $level = Logger::INFO, $context = array());

    /**
     * Write text to the output without a newline at the end
     *
     * @param string $message The message to write
     * @param int $level The log level used, one of the constants from Monolog\Logger
     */
    public function write($message, $level = Logger::INFO);

    /**
     * Create and display a progress bar. If the output does not support progress bars, nothing is done.
     *
     * @param int $max Maximum steps (0 if unknown)
     */
    public function progressStart($max = 0);

    /**
     * Advance the progress bar created by progressStart(). If the output does not support progress bars, nothing is done.
     * If no progress bar has been started via progressStart(), an Exception is thrown.
     *
     * @param int $step Number of steps to advance
     */
    public function progressAdvance($step = 1);

    /**
     * Finish the progress bar created by progressStart(). If the output does not support progress bars, nothing is done.
     * If no progress bar has been started via progressStart(), an Exception is thrown.
     */
    public function progressFinish();
}
