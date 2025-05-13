<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Security\CsrfChecker;
use Enhavo\Bundle\UserBundle\Security\Authentication\Badge\CsrfDisableFormProtectionBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CsrfDisableSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CsrfChecker $csrfChecker,
    ) {
    }

    public function checkPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        if (!$passport->hasBadge(CsrfDisableFormProtectionBadge::class)) {
            return;
        }

        /** @var CsrfDisableFormProtectionBadge $badge */
        $badge = $passport->getBadge(CsrfDisableFormProtectionBadge::class);
        if ($badge->isResolved()) {
            return;
        }

        $this->csrfChecker->disable();

        $badge->markResolved();
    }

    public static function getSubscribedEvents(): array
    {
        return [CheckPassportEvent::class => ['checkPassport', 512]];
    }
}
