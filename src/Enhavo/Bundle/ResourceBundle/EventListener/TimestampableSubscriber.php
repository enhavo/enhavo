<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TimestampableSubscriber implements EventSubscriberInterface
{
    // referenced as string, so the framework bundle is not required
    private const TIMESTAMPABLE = 'Enhavo\\Bundle\\FrameworkBundle\\Model\\Timestampable';

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave',
        ];
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();

        if (is_a($resource, self::TIMESTAMPABLE)) {
            $now = new \DateTime();
            $resource->updatedAt = $now;
            if (null === $resource->createdAt) {
                $resource->createdAt = $now;
            }
        }
    }
}
