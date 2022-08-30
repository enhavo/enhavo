<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SaveUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ResourceEvents::PRE_CREATE => ['onSave', 1],
            ResourceEvents::PRE_UPDATE => ['onSave', 1],
        ];
    }

    public function onSave(ResourceEvent $event)
    {
        $user = $event->getSubject();
        if ($user instanceof UserInterface) {
            $this->userManager->update($user);
        }
    }
}
