<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\EventListener;

use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PublishSubscriber implements EventSubscriberInterface
{
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

        if ($resource instanceof Publishable) {
            if ($resource->isPublic() && null === $resource->getPublicationDate()) {
                $resource->setPublicationDate(new \DateTime());
            }
        }
    }
}
