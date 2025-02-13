<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 06/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Proxy;
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
    private array $scheduleDeleted = [];

    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly EntityResolverInterface $entityResolver,
    )
    {
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
    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
            Events::preFlush,
            Events::postFlush,
        ];
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        // Insert target class on load

        $object = $args->getObject();

        $metadata = $this->getMetadata($object);
        if ($metadata === null) {
            return;
        }

        if ($this->getClass($object) === $metadata->getClassName() || $this->isParentClass($object, $metadata->getClassName())) {
            foreach ($metadata->getReferences() as $reference) {
                $this->loadEntity($reference, $object);
            }
        }
    }

    private function isParentClass($object, $class): bool
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

    private function loadEntity(Reference $reference, $entity): void
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
    public function preFlush(PreFlushEventArgs $args): void
    {
        $uow = $args->getObjectManager()->getUnitOfWork();
        $em = $args->getObjectManager();
        $identityMap = $uow->getIdentityMap();

        foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $scheduledEntityDeletions) {
            $this->scheduleDeleted[] = $scheduledEntityDeletions;
        }

        foreach ($this->getAllMetadata() as $metadata) {
            if (isset($identityMap[$metadata->getClassName()])) {
                foreach ($identityMap[$metadata->getClassName()] as $entity) {
                    foreach ($metadata->getReferences() as $reference) {
                        $this->updateEntity($reference, $entity);
                        if ($reference->hasCascade(Reference::CASCADE_PERSIST)) {
                            $this->updatePersist($reference, $entity, $em);
                        }
                    }
                }
            }

            foreach ($uow->getScheduledEntityInsertions() as $entity) {
                if ($this->getClass($entity) === $metadata->getClassName()) {
                    foreach ($metadata->getReferences() as $reference) {
                        $this->updateEntity($reference, $entity);
                        if ($reference->hasCascade(Reference::CASCADE_PERSIST)) {
                            $this->updatePersist($reference, $entity, $em);
                        }
                    }
                }
            }

            foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $entity) {
                if ($this->getClass($entity) === $metadata->getClassName()) {
                    foreach ($metadata->getReferences() as $reference) {
                        if ($reference->hasCascade(Reference::CASCADE_REMOVE)) {
                            $propertyAccessor = PropertyAccess::createPropertyAccessor();
                            $targetEntity = $propertyAccessor->getValue($entity, $reference->getProperty());
                            if ($targetEntity !== null && !in_array($targetEntity, $this->scheduleDeleted)) {
                                $em->remove($targetEntity);
                            }
                        }
                    }
                }
            }
        }
    }

    private function updatePersist(Reference $reference, $entity, EntityManagerInterface $em): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $targetProperty = $propertyAccessor->getValue($entity, $reference->getProperty());

        if ($targetProperty
            && $em->getUnitOfWork()->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED // is not managed yet
            && !in_array($targetProperty, $this->scheduleDeleted) // should not be deleted
        ) {
            $em->persist($targetProperty);
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        // Check if entity is not up-to-date and execute changes

        $persist = false;
        $changes = [];

        $em = $args->getObjectManager();
        $uow = $args->getObjectManager()->getUnitOfWork();
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

                        if ($targetProperty
                            && $uow->getEntityState($targetProperty) !== UnitOfWork::STATE_MANAGED // is not managed yet
                            && !in_array($targetProperty, $this->scheduleDeleted) // should not be deleted
                            && $reference->hasCascade(Reference::CASCADE_PERSIST) // should persist
                        ) {
                            $em->persist($targetProperty);
                            $persist = true;
                        }
                    }
                }
            }
        }

        if (count($changes)) {
            $this->executeChanges($changes, $args->getObjectManager());
        }

        if ($persist) {
            $em->flush();
        }

        $this->scheduleDeleted = [];
    }

    /**
     * Update entity values
     */
    private function updateEntity(Reference $reference, object $entity): array
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
     */
    private function executeChanges(array $changes, EntityManagerInterface $em): void
    {
        $queries = [];
        $entities = [];
        foreach ($changes as $change) {
            $query = $em->createQueryBuilder()
                ->update($this->getClass($change->getEntity()), 'e')
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

    private function getClass($object): string
    {
        if ($object instanceof Proxy) {
            return get_parent_class($object);
        }

        return get_class($object);
    }
}
