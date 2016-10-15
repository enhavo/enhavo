<?php
/**
 * PublishSubscriber.php
 *
 * @since 15/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\EventListener;

use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

class PublishSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_app.pre_create' => 'preSave',
            'enhavo_app.pre_update' => 'preSave'
        );
    }

    public function preSave(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();

        if($resource instanceof Publishable) {
            if($resource->isPublic() && $resource->getPublicationDate() === null) {
                $resource->setPublicationDate(new \DateTime());
            }
        }
    }
}