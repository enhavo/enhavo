<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LastLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::LOGIN_SUCCESS => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(UserEvent $event)
    {
        $this->userManager->updateLoggedIn($event->getUser());
    }
}
