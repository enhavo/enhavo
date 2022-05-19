<?php
/**
 * PublishSubscriber.php
 *
 * @since 15/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PublishSubscriber implements EventSubscriberInterface
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

        if ($resource instanceof Publishable) {
            if ($resource->isPublic() && $resource->getPublicationDate() === null) {
                $resource->setPublicationDate(new \DateTime());
            }
        }
    }
}
