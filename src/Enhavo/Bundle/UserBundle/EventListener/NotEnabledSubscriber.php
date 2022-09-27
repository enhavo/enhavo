<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotEnabledSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::POST_AUTH => 'onPostAuth',
        ];
    }

    public function onPostAuth(UserEvent $userEvent): void
    {
        if (!$userEvent->getUser()->isEnabled()) {
            $exception = new NotEnabledException('Not enabled');
            $exception->setUser($userEvent->getUser());
            $userEvent->setException($exception);
        }
    }
}
