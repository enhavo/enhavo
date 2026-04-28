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
use Symfony\Component\DependencyInjection\ServiceLocator;

class InitManager
{
    private ?ServiceLocator $container = null;

    public function setContainer(ServiceLocator $container): void
    {
        $this->container = $container;
    }

    public function init(OutputInterface $output): void
    {
        $io = new Output($output);
        /** @var InitInterface $initializer */
        foreach ($this->container as $key => $service) {
            $io->writeln(sprintf('<info>%s</info>', $key));
            $service->init($io);
        }
    }
}
