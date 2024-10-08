<?php
/**
 * PublishSubscriber.php
 *
 * @since 15/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\EventListener;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SaveSubscriber implements EventSubscriberInterface
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

        if ($resource instanceof Content) {
            if ($resource->getSlug() === null) {
                $resource->setSlug(Slugifier::slugify('title'));
            }
        }
    }
}
