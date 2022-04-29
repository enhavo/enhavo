<?php
/**
 * PublishSubscriber.php
 *
 * @since 15/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\EventListener;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_app.pre_update' => 'preSave'
        );
    }

    public function preSave(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();

        if ($resource instanceof Content) {
            $resource->setUpdated(new \DateTime());
        }
    }
}
