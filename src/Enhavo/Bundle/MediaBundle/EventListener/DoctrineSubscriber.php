<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.10.17
 * Time: 13:46
 */

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var FormatManager
     */
    private $formatManager;

    public function __construct(StorageInterface $storage, FormatManager $formatManager)
    {
        $this->storage = $storage;
        $this->formatManager = $formatManager;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
            Events::postUpdate,
            Events::postPersist,
            Events::preRemove,
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof FileInterface) {
            $this->storage->saveFile($object);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof FileInterface) {
            $this->storage->saveFile($object);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof FileInterface) {
            $this->storage->applyContent($object);
        } elseif($object instanceof FormatInterface) {
            $this->storage->applyContent($object);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof FileInterface) {
            $this->storage->deleteFile($object);
            // Delete on files doesn't use a doctrine cascade to formats, but an SQL cascade instead. This means that formats will be deleted without triggering a doctrine event.
            // So to clean up files associated with formats, we must delete them in the doctrine event for the file instead of the format.
            $this->formatManager->deleteFormats($object);
        }
    }
}