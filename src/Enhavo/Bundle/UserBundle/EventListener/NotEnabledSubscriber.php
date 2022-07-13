<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotEnabledSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::class => 'onUserEvent',
        ];
    }

    /**
     * @throws Exception
     */
    public function onUserEvent(UserEvent $userEvent): void
    {
        if ($userEvent->getType() === UserEvent::TYPE_PRE_AUTH) {
            if (false === $userEvent->getUser()->isEnabled()) {
                $exception = new NotEnabledException('Not enabled');
                $exception->setUser($userEvent->getUser());
                $userEvent->setException($exception);
            }
        }
    }
}
