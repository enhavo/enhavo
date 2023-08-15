<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-26
 * Time: 00:45
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class TimestampableSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave'
        );
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();

        if ($resource instanceof Timestampable) {
            $now = new \DateTime();
            $resource->setUpdatedAt($now);
            if ($resource->getCreatedAt() === null) {
                $resource->setCreatedAt($now);
            }
        }
    }
}
