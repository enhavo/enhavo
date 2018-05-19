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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
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
     * @var boolean
     */
    private $flush;

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
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
            Events::onFlush,
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        // persist target class
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $result = $uow->getIdentityMap();
        if(isset($result[$this->targetClass])) {
            foreach ($result[$this->targetClass] as $entity) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $targetProperty = $propertyAccessor->getValue($entity, $this->targetProperty);
                if($targetProperty && $uow->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED) {
                    $em->persist($targetProperty);
                    $metaData = $em->getClassMetadata(get_class($targetProperty));
                    $uow->computeChangeSet($metaData, $targetProperty);
                }
            }
        }
    }

    /**
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

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateEntity($args->getObject());
        $this->flush = true;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateEntity($args->getObject());
        $this->flush = true;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateReferences($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->updateReferences($args);
    }

    private function updateReferences(LifecycleEventArgs $args)
    {
        if($this->flush)
        {
            $em = $args->getObjectManager();
            $changes = false;
            if($em instanceof EntityManagerInterface) {
                $uow = $em->getUnitOfWork();
                $result = $uow->getIdentityMap();
                if(isset($result[$this->targetClass])) {
                    foreach ($result[$this->targetClass] as $entity) {
                        $change = $this->updateEntity($entity);
                        if($change) {
                            $changes = true;
                        }
                    }
                }
            }

            $this->flush = false;
            if($changes) {
                $em->flush();
            }
        }
    }

    /**
     * @param object $entity
     * @return bool
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
            }
        }

        return $change;
    }
}