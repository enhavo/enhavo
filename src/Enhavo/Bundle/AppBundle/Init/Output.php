<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Init;

use Symfony\Component\Console\Output\OutputInterface;

class Output
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function writeln($message)
    {
        $this->output->writeln($message);
    }

    public function write($message)
    {
        $this->output->write($message);
    }
}
