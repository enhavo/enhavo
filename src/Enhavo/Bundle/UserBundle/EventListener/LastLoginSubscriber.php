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
            UserEvent::class => 'onLogin',
        ];
    }

    public function onLogin(UserEvent $event)
    {
        if ($event->getType() === UserEvent::TYPE_LOGIN_SUCCESS) {
            $this->userManager->updateLoggedIn($event->getUser());
        }
    }
}
