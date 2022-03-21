<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 06/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Proxy\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Reference;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\UnitOfWork;

/**
 * Class ReferenceSubscriber
 *
 * A reference extension for doctrine, to reference multiple entities within one property.
 */
class ReferenceSubscriber implements EventSubscriber
{
    /** @var MetadataRepository */
    private $metadataRepository;

    /** @var EntityResolverInterface */
    private $entityResolver;

    public function __construct(MetadataRepository $metadataRepository, EntityResolverInterface $entityResolver)
    {
        $this->metadataRepository = $metadataRepository;
        $this->entityResolver = $entityResolver;
    }

    /**
     * @param $object
     * @return Metadata|null
     */
    private function getMetadata($object): ?Metadata
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($object);
        if ($metadata === null) {
            return null;
        }
        return $metadata;
    }

    /**
     * @return Metadata[]
     */
    private function getAllMetadata(): array
    {
        $data = [];
        /** @var Metadata $metadata */
        foreach ($this->metadataRepository->getAllMetadata() as $metadata) {
            if (count($metadata->getReferences()) > 0 && !in_array($metadata, $data)) {
                $data[] = $metadata;
            }
        }
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::preFlush,
            Events::postFlush,
        ];
    }

    /**
     * Insert target class on load
     *
     * @param $args LifecycleEventArgs
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        $metadata = $this->getMetadata($object);
        if ($metadata === null) {
            return;
        }

        if (get_class($object) === $metadata->getClassName() || $this->isParentClass($object, $metadata->getClassName())) {
            foreach ($metadata->getReferences() as $reference) {
                $this->loadEntity($reference, $object);
            }
        }
    }

    private function isParentClass($object, $class)
    {
        $parentClass = get_parent_class($object);
        if ($parentClass === false) {
            return false;
        } elseif ($parentClass === $class) {
            return true;
        } else {
            return $this->isParentClass($parentClass, $class);
        }
    }

    private function loadEntity(Reference $reference, $entity)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $id = $propertyAccessor->getValue($entity, $reference->getIdField());
        $class = $propertyAccessor->getValue($entity, $reference->getNameField());

        if ($id && $class) {
            $targetEntity = $this->entityResolver->getEntity($id, $class);
            if ($targetEntity !== null) {
                $propertyAccessor->setValue($entity, $reference->getProperty(), $targetEntity);
            }
        }
    }

    /**
     * Update entity before flush to prevent possible changes after flush
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $uow = $args->getEntityManager()->getUnitOfWork();
        $em = $args->getEntityManager();
        $identityMap = $uow->getIdentityMap();

        foreach ($this->getAllMetadata() as $metadata) {
            if (isset($identityMap[$metadata->getClassName()])) {
                foreach ($identityMap[$metadata->getClassName()] as $entity) {
                    foreach ($metadata->getReferences() as $reference) {
                        $this->updateEntity($reference, $entity);
                        $this->updatePersist($reference, $entity, $em);
                    }
                }
            }

            foreach ($uow->getScheduledEntityInsertions() as $entity) {
                if (get_class($entity) === $metadata->getClassName()) {
                    foreach ($metadata->getReferences() as $reference) {
                        $this->updateEntity($reference, $entity);
                        $this->updatePersist($reference, $entity, $em);
                    }
                }
            }
        }
    }

    private function updatePersist(Reference $reference, $entity, EntityManagerInterface $em)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $targetProperty = $propertyAccessor->getValue($entity, $reference->getProperty());

        if ($targetProperty && $em->getUnitOfWork()->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED) {
            $em->persist($targetProperty);
        }
    }

    /**
     * Check if entity is not up to date and execute changes
     *
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $persist = false;
        $changes = [];

        $em = $args->getEntityManager();
        $uow = $args->getEntityManager()->getUnitOfWork();
        $result = $uow->getIdentityMap();

        foreach ($this->getAllMetadata() as $metadata) {
            if (isset($result[$metadata->getClassName()])) {
                foreach ($result[$metadata->getClassName()] as $entity) {
                    foreach ($metadata->getReferences() as $reference) {
                        $entityChanges = $this->updateEntity($reference, $entity);
                        foreach ($entityChanges as $entityChange) {
                            $changes[] = $entityChange;
                        }

                        $propertyAccessor = PropertyAccess::createPropertyAccessor();
                        $targetProperty = $propertyAccessor->getValue($entity, $reference->getProperty());

                        if ($targetProperty && $uow->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED) {
                            $em->persist($targetProperty);
                            $persist = true;
                        }
                    }
                }
            }
        }

        if (count($changes)) {
            $this->executeChanges($changes, $args->getEntityManager());
        }

        if ($persist) {
            $em->flush();
        }
    }

    /**
     * Update entity values
     *
     * @param Reference $reference
     * @param object $entity
     * @return ReferenceChange[]
     */
    private function updateEntity(Reference $reference, $entity)
    {
        $changes = [];

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $targetProperty = $propertyAccessor->getValue($entity, $reference->getProperty());

        // if entity state is still proxy, we try to load its entity to make sure that target property is correct
        if ($entity instanceof Proxy && $targetProperty === null) {
            $this->loadEntity($reference, $entity);
            $targetProperty = $propertyAccessor->getValue($entity, $reference->getProperty());
        }

        if ($targetProperty) {
            // update id property
            $idProperty = $propertyAccessor->getValue($entity, $reference->getIdField());
            $id = $propertyAccessor->getValue($targetProperty, 'id');
            if ($idProperty != $id) {
                $propertyAccessor->setValue($entity, $reference->getIdField(), $id);
                $changes[] = new ReferenceChange($entity, $reference->getIdField(), $id);
            }

            // update class property
            $classProperty = $propertyAccessor->getValue($entity, $reference->getNameField());
            $class = $this->entityResolver->getName($targetProperty);
            if ($classProperty != $class) {
                $propertyAccessor->setValue($entity, $reference->getNameField(), $class);
                $changes[] = new ReferenceChange($entity, $reference->getNameField(), $class);
            }
        } else {
            if (null !== $propertyAccessor->getValue($entity, $reference->getIdField())) {
                $propertyAccessor->setValue($entity, $reference->getIdField(), null);
                $changes[] = new ReferenceChange($entity, $reference->getIdField(), null);
            }

            if (null !== $propertyAccessor->getValue($entity, $reference->getNameField())) {
                $propertyAccessor->setValue($entity, $reference->getNameField(), null);
                $changes[] = new ReferenceChange($entity, $reference->getNameField(), null);
            }
        }

        return $changes;
    }

    /**
     * @param ReferenceChange[] $changes
     * @param EntityManagerInterface $em
     */
    private function executeChanges($changes, EntityManagerInterface $em)
    {
        $queries = [];
        $entities = [];
        foreach ($changes as $change) {
            $query = $em->createQueryBuilder()
                ->update(get_class($change->getEntity()), 'e')
                ->set(sprintf('e.%s', $change->getProperty()), ':value')
                ->where('e.id = :id')
                ->setParameter('value', $change->getValue())
                ->setParameter('id', $change->getEntity()->getId())
                ->getQuery()
            ;

            $queries[] = $query;

            if (!in_array($change->getEntity(), $entities)) {
                $entities[] = $change->getEntity();
            }
        }

        foreach ($queries as $query) {
            $query->execute();
        }

        foreach ($entities as $entity) {
            $em->refresh($entity);
        }
    }
}
