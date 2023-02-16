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
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class DoctrineSubscriber implements EventSubscriber
{
    public function __construct(
        private StorageInterface  $storage,
    ) {}

    public function getSubscribedEvents(): array
    {
        return array(
            Events::postLoad,
            Events::postUpdate,
            Events::postPersist,
            Events::preRemove,
        );
    }

    public function postUpdate(PostUpdateEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface) {
            $this->storage->saveFile($object);
        }
    }

    public function postPersist(PostPersistEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface) {
            $this->storage->saveFile($object);
        }
    }

    public function postLoad(PostLoadEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface) {
            $this->storage->applyContent($object);
        } elseif ($object instanceof FormatInterface) {
            $this->storage->applyContent($object);
        }
    }

    public function preRemove(PreRemoveEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof FileInterface) {
            $this->storage->deleteFile($object);
        } elseif ($object instanceof FormatInterface) {
            $this->storage->deleteFile($object);
        }
    }

}
