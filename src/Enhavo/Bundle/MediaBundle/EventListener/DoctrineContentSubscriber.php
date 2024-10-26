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
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class DoctrineContentSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly StorageInterface $storage,
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
            $object->setContent($this->storage->saveContent($object));
        }
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            $object->setContent($this->storage->saveContent($object));
        }
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            $object->setContent($this->storage->getContent($object));
        }
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface || $object instanceof FormatInterface) {
            $this->storage->deleteContent($object);
        }
    }
}
