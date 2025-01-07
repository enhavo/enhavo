<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;
use Enhavo\Bundle\UserBundle\Model\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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

        if ($user instanceof User && !$user->isEnabled()) {
            $exception = new NotEnabledException('Not enabled');
            $exception->setUser($user);
            $userEvent->setException($exception);
        }
    }
}
