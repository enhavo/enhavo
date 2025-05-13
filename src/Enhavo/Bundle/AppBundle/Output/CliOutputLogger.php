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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CliOutputLogger extends AbstractOutputLogger
{
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var int
     */
    private $verboseRequiredLevel = Level::Debug;

    /**
     * CliOutputLogger constructor.
     */
    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    /**
     * @param int $verboseRequiredLevel
     */
    public function setVerboseRequiredLevel($verboseRequiredLevel)
    {
        $this->verboseRequiredLevel = $verboseRequiredLevel;
    }

    /**
     * @return int
     */
    public function getVerboseRequiredLevel()
    {
        return $this->verboseRequiredLevel;
    }

    /**
     * @return SymfonyStyle|null
     */
    public function getSymfonyStyle()
    {
        return $this->symfonyStyle;
    }

    public function writeln($message, $level = Level::Info, $context = [])
    {
        $type = $level <= $this->getVerboseRequiredLevel() ? OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE : OutputInterface::OUTPUT_NORMAL;
        $this->symfonyStyle->writeln($message, $type);
    }

    public function write($message, $level = Level::Info)
    {
        $type = $level <= $this->getVerboseRequiredLevel() ? OutputInterface::OUTPUT_NORMAL | OutputInterface::VERBOSITY_VERBOSE : OutputInterface::OUTPUT_NORMAL;
        $this->symfonyStyle->write($message, false, $type);
    }

    public function progressStart($max = 0)
    {
        $this->symfonyStyle->progressStart($max);
    }

    public function progressAdvance($step = 1)
    {
        $this->symfonyStyle->progressAdvance($step);
    }

    public function progressFinish()
    {
        $this->symfonyStyle->progressFinish();
    }
}
