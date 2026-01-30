<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\Init;

use Symfony\Component\Console\Output\OutputInterface;

class InitManager
{
    public function init(OutputInterface $output)
    {
        $io = new Output($output);
        /** @var InitInterface $initializer */
//        foreach ($this->collector->getTypes() as $initializer) {
//            $io->writeln('Initializer');
//            $initializer->init($io);
//        }
    }
}
