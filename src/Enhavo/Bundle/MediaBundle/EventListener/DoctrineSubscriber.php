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
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
            'postUpdate',
            'postPersist',
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
            $this->storage->saveFile($object);;
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
}