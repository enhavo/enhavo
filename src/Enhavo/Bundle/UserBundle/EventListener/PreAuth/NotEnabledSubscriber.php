<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;

abstract class NotEnabledSubscriber extends AbstractPreAuthSubscriber
{
    public function checkPreAuth(UserEvent $event): void
    {
        if (false === $event->getUser()->isEnabled()) {
            $event->setException(new NotEnabledException('Not enabled'));
        }
    }

}
