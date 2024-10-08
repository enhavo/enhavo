<?php
/**
 * @author blutze-media
 * @since 2022-05-20
 */

namespace Enhavo\Bundle\MediaLibraryBundle\EventListener;

use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentTypeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MediaLibraryManager $mediaLibraryManager,
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => 'preCreate',
        );
    }

    public function preCreate(ResourceEvent $event)
    {
        $resource = $event->getSubject();

        if ($resource instanceof File) {
            $resource->setContentType($this->mediaLibraryManager->matchContentType($resource));
        }
    }
}
