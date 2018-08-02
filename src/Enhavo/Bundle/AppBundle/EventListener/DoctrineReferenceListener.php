<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 06/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\AppBundle\Reference\TargetClassResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\UnitOfWork;

/**
 * Class DoctrineReferenceListener
 *
 * A reference extension for doctrine, to reference multiple entities within one property.
 *
 * @package Enhavo\Bundle\ArticleBundle\EventListener
 */
class DoctrineReferenceListener implements EventSubscriber
{
    /**
     * @var string
     */
    private $targetClass;

    /**
     * @var string
     */
    private $targetProperty;

    /**
     * @var string
     */
    private $classProperty;

    /**
     * @var integer
     */
    private $idProperty;

    /**
     * @var TargetClassResolverInterface
     */
    private $targetClassResolver;

    /**
     * @var array
     */
    private $referencesToUpdate = [];

    public function __construct($targetClass, $targetProperty, $classProperty, $idProperty, $targetClassResolver)
    {
        $this->targetClass = $targetClass;
        $this->targetProperty = $targetProperty;
        $this->classProperty = $classProperty;
        $this->idProperty = $idProperty;
        $this->targetClassResolver = $targetClassResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
            Events::preFlush,
            Events::onFlush,
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
        );
    }

    /**
     * Insert target class on load
     *
     * @param $args LifecycleEventArgs
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if(get_class($object) === $this->targetClass) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $id = $propertyAccessor->getValue($object, $this->idProperty);
            $class = $propertyAccessor->getValue($object, $this->classProperty);

            if($id && $class) {
                $targetEntity = $this->targetClassResolver->find($id, $class);
                if($targetEntity !== null) {
                    $propertyAccessor->setValue($object, $this->targetProperty, $targetEntity);
                }
            }
        }
    }

    /**
     * Persist target class and check if need a reference update for already saved entities
     *
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $result = $uow->getIdentityMap();
        if(isset($result[$this->targetClass])) {
            foreach ($result[$this->targetClass] as $entity) {
                $change = false;

                $updated = $this->updateEntity($entity);
                if($updated) {
                    $change = true;
                }

                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);
                if($targetProperty && $uow->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED) {
                    $em->persist($targetProperty);
                    $change = true;
                }

                if($change) {
                    $metaData = $em->getClassMetadata(get_class($targetProperty));
                    $uow->computeChangeSet($metaData, $targetProperty);
                }

                if($targetProperty && $propertyAccessor->getValue($entity, $this->idProperty) === null) {
                    $this->referencesToUpdate[] = $entity;
                }
            }
        }
    }

    /**
     * Persist target class and update entity and check if need a reference update for new entities
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $result = $uow->getScheduledEntityInsertions();
        foreach($result as $entity) {
            if(get_class($entity) == $this->targetClass) {
                $this->updateEntity($entity);
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);
                if($targetProperty && $uow->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED) {
                    $em->persist($targetProperty);
                }
                if($targetProperty && $propertyAccessor->getValue($entity, $this->idProperty) === null) {
                    $this->referencesToUpdate[] = $entity;
                }
            }
        }
    }

    /**
     * Guarantee update references if needed
     *
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateReferences($args);
    }

    /**
     * Guarantee update references if needed
     *
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->updateReferences($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateEntity($args->getObject());
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateEntity($args->getObject());
    }

    /**
     * Update references from references to update stack
     *
     * @param LifecycleEventArgs $args
     */
    private function updateReferences(LifecycleEventArgs $args)
    {
        $flush = false;
        while(count($this->referencesToUpdate) > 0) {
            $entity = array_pop($this->referencesToUpdate);
            $this->updateEntity($entity);
            $flush = true;
        }

        if($flush) {
            $em = $args->getObjectManager();
            $em->flush();
        }
    }

    /**
     * Update entity values
     *
     * @param object $entity
     * @return boolean
     */
    private function updateEntity($entity)
    {
        $change = false;
        if(get_class($entity) == $this->targetClass) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);

            if($targetProperty) {
                // update id property
                $idProperty = $propertyAccessor->getValue($entity, $this->idProperty);
                $id = $propertyAccessor->getValue($targetProperty, 'id');
                if($idProperty != $id) {
                    $propertyAccessor->setValue($entity, $this->idProperty, $id);
                    $change = true;
                }

                // update class property
                $classProperty = $propertyAccessor->getValue($entity, $this->classProperty);
                $class = $this->targetClassResolver->resolveClass($targetProperty);
                if($classProperty != $class) {
                    $propertyAccessor->setValue($entity, $this->classProperty, $class);
                    $change = true;
                }
            } else {
                if(null !== $propertyAccessor->getValue($entity, $this->idProperty)) {
                    $propertyAccessor->setValue($entity, $this->idProperty, null);
                    $change = true;
                }

                if(null !== $propertyAccessor->getValue($entity, $this->classProperty)) {
                    $propertyAccessor->setValue($entity, $this->classProperty, null);
                    $change = true;
                }

            }
        }
        return $change;
    }
}