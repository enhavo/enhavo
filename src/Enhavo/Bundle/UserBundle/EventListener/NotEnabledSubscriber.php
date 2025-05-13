<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author blutze
 */
class NotEnabledSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::PRE_AUTH => 'onPreAuth',
        ];
    }

    public function onPreAuth(UserEvent $userEvent): void
    {
        $user = $userEvent->getUser();

        if ($user instanceof UserInterface && !$user->isEnabled()) {
            $exception = new NotEnabledException('Not enabled');
            $exception->setUser($user);
            $userEvent->setException($exception);
        }
    }
}
