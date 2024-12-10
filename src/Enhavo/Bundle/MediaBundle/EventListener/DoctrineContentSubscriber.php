<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.10.17
 * Time: 13:46
 */

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\FileNotFound\FileNotFoundHandlerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class DoctrineContentSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly StorageInterface $storage,
        private readonly FileNotFoundHandlerInterface $handler,
        private readonly array $fileNotFoundHandlerParameters,
    ) {}

    public function getSubscribedEvents(): array
    {
        return array(
            Events::postLoad,
            Events::postUpdate,
            Events::postPersist,
            Events::postRemove,
        );
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            try {
                $object->setContent($this->storage->saveContent($object));
            } catch (FileNotFoundException $exception) {
                $this->handler->handleSave($object, $this->storage, $exception, $this->fileNotFoundHandlerParameters);
            }
        }
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            try {
                $object->setContent($this->storage->saveContent($object));
            } catch (FileNotFoundException $exception) {
                $this->handler->handleSave($object, $this->storage, $exception, $this->fileNotFoundHandlerParameters);
            }
        }
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            try {
                $object->setContent($this->storage->getContent($object));
            } catch (FileNotFoundException $exception) {
                $this->handler->handleLoad($object, $this->storage, $exception, $this->fileNotFoundHandlerParameters);
            }
        }
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            try {
                $this->storage->deleteContent($object);
            } catch (FileNotFoundException $exception) {
                $this->handler->handleDelete($object, $this->storage, $exception, $this->fileNotFoundHandlerParameters);
            }
        }
    }
}
