<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Kernel;

use Behat\Behat\EventDispatcher\Event\ExampleTested;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly KernelManager $kernelManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScenarioTested::AFTER => 'shutdown',
            ExampleTested::AFTER => 'shutdown',
        ];
    }

    public function shutdown(): void
    {
        $this->kernelManager->shutdown();
    }
}
