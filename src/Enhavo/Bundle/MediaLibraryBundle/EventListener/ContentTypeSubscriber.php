<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\EventListener;

use Enhavo\Bundle\MediaLibraryBundle\Entity\Item;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentTypeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MediaLibraryManager $mediaLibraryManager,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => 'preCreate',
        ];
    }

    public function preCreate(ResourceEvent $event)
    {
        $resource = $event->getSubject();

        if ($resource instanceof Item) {
            $resource->setContentType($this->mediaLibraryManager->matchContentType($resource->getFile()));
        }
    }
}
