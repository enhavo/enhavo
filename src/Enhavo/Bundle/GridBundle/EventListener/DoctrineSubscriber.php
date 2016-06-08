<?php
/**
 * DoctrineSubscriber.php
 *
 * @since 05/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Item\ItemTypeResolver;
use Enhavo\Bundle\GridBundle\Exception\NoTypeFoundException;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

    public function __construct(ItemTypeResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'postPersist',
            'postLoad',
            'preRemove',
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $manager = $args->getEntityManager();
        if ($entity instanceof Item) {
            $itemType = $entity->getItemType();
            $manager->persist($itemType);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $manager = $args->getEntityManager();
        if ($entity instanceof Item) {
            $itemType = $entity->getItemType();
            //if itemType id was not set yet, force setting the id
            if($itemType->getId() === null) {
                $manager->flush();
            }
            $entity->setType($this->resolver->getType($itemType));
            $entity->setItemTypeId($itemType->getId());
            $manager->flush();
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Item) {
            try {
                $repository = $this->resolver->getRepository($entity->getType());
                $itemType = $repository->find($entity->getItemTypeId());
                $entity->setItemType($itemType);
            } catch(NoTypeFoundException $e ) {
                //@TODO: log something
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Item) {
            $itemType = $entity->getItemType();
            $args->getEntityManager()->remove($itemType);
        }
    }
}
