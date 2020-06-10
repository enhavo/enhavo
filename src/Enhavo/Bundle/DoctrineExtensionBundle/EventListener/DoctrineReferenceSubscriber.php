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
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Proxy\Proxy;
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
class DoctrineReferenceSubscriber implements EventSubscriber
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
            Events::postFlush,
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
        if(get_class($object) === $this->targetClass || $this->isParentClass($object, $this->targetClass)) {
            $this->loadEntity($object);
        }
    }

    private function isParentClass($object, $class)
    {
        $parentClass = get_parent_class($object);
        if($parentClass === false) {
            return false;
        } elseif($parentClass === $class) {
            return true;
        } else {
            return $this->isParentClass($parentClass, $class);
        }
    }

    private function loadEntity($entity)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $id = $propertyAccessor->getValue($entity, $this->idProperty);
        $class = $propertyAccessor->getValue($entity, $this->classProperty);

        if($id && $class) {
            $targetEntity = $this->targetClassResolver->find($id, $class);
            if($targetEntity !== null) {
                $propertyAccessor->setValue($entity, $this->targetProperty, $targetEntity);
            }
        }
    }

    /**
     * Update entity before flush to prevent a possible after flush
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $uow = $args->getEntityManager()->getUnitOfWork();
        $result = $uow->getIdentityMap();
        if(isset($result[$this->targetClass])) {
            foreach ($result[$this->targetClass] as $entity) {
                $this->updateEntity($entity);
            }
        }
    }

    /**
     * Check if entity is not up to date an trigger flush again if needed
     *
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $change = false;

        $em = $args->getEntityManager();
        $uow = $args->getEntityManager()->getUnitOfWork();
        $result = $uow->getIdentityMap();
        if(isset($result[$this->targetClass])) {
            foreach ($result[$this->targetClass] as $entity) {
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
            }
        }

        if($change) {
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

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);

        // if entity state is still proxy, we try to load its entity to make sure that target property is correct
        if($entity instanceof Proxy && $targetProperty === null) {
            $this->loadEntity($entity);
            $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);
        }

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

        return $change;
    }
}
