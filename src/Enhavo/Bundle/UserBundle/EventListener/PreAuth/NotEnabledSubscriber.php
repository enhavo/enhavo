<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;

class NotEnabledSubscriber extends AbstractPreAuthSubscriber
{
    public function onPreAuth(UserEvent $event): void
    {
        if (false === $event->getUser()->isEnabled()) {
            $exception = new NotEnabledException('Not enabled');
            $exception->setUser($event->getUser());
            $event->setException($exception);
        }
    }

}
