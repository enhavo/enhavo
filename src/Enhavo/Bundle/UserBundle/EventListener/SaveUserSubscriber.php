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

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SaveUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResourceEvents::PRE_CREATE => ['onSave', 1],
            ResourceEvents::PRE_UPDATE => ['onSave', 1],
        ];
    }

    public function onSave(ResourceEvent $event): void
    {
        $user = $event->getSubject();
        if ($user instanceof UserInterface) {
            $this->userManager->update($user, false);
        }
    }
}
