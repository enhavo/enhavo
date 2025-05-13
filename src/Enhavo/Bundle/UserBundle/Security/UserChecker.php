<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Model\UserInterface as EnhavoUserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author gseidel
 */
class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof EnhavoUserInterface) {
            return;
        }

        $event = new UserEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvent::PRE_AUTH);

        $exception = $event->getException();
        if ($exception) {
            throw $exception;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof EnhavoUserInterface) {
            return;
        }

        $event = new UserEvent($user);
        $this->eventDispatcher->dispatch($event, UserEvent::POST_AUTH);

        $exception = $event->getException();
        if ($exception) {
            throw $exception;
        }
    }
}
