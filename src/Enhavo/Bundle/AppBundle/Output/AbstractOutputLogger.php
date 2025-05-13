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
use Monolog\Logger;

abstract class AbstractOutputLogger implements OutputLoggerInterface
{
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Emergency, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Alert, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Critical, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Error, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Warning, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Notice, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Info, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->writeln($message, Level::Debug, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $level = Logger::toMonologLevel($level);
        $this->writeln($message, $level, $context);
    }
}
