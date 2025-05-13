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

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Event\PostUploadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FileUploadSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            PostUploadEvent::DEFAULT_EVENT_NAME => 'defaultPostUpload',
            'enhavo_media_library.post_upload_library' => 'libraryPostUpload',
        ];
    }

    public function defaultPostUpload(PostUploadEvent $event)
    {
        $file = $event->getSubject();

        $file->setLibrary(false);
        $this->entityManager->flush();
    }

    public function libraryPostUpload(PostUploadEvent $event)
    {
        $file = $event->getSubject();

        $file->setLibrary(true);
        $file->setGarbage(false);
        $this->entityManager->flush();
    }
}
