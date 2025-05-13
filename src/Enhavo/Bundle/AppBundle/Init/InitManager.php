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

use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitManager
{
    /**
     * @var CollectorInterface
     */
    private $collector;

    /**
     * InitManager constructor.
     */
    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function init(OutputInterface $output)
    {
        $io = new Output($output);
        /** @var InitInterface $initializer */
        foreach ($this->collector->getTypes() as $initializer) {
            $io->writeln('Initializer');
            $initializer->init($io);
        }
    }
}
