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
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Reference;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ReferenceSubscriber
 *
 * A reference extension for doctrine, to reference multiple entities within one property.
 */
readonly class ReferenceSubscriber implements EventSubscriber
{
    public function __construct(
        private MetadataRepository $metadataRepository,
        private EntityResolverInterface $entityResolver,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
            Events::preFlush,
            Events::postFlush,
            Events::prePersist,
            Events::preRemove,
        ];
    }

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

    private function getClass($object): string
    {
        if ($object instanceof Proxy) {
            return get_parent_class($object);
        }

        return get_class($object);
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        // Insert target class on load

        $object = $args->getObject();

        $metadata = $this->getMetadata($this->getClass($object));
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

    public function prePersist(PrePersistEventArgs $args): void
    {
        $uow = $args->getObjectManager()->getUnitOfWork();

        $metadata = $this->getMetadata($args->getObject());
        if ($metadata !== null) {
            foreach ($metadata->getReferences() as $reference) {
                if ($reference->hasCascade(Reference::CASCADE_PERSIST)) {
                    $propertyAccessor = PropertyAccess::createPropertyAccessor();
                    $targetEntity = $propertyAccessor->getValue($args->getObject(), $reference->getProperty());
                    if ($targetEntity !== null &&
                        !$uow->isInIdentityMap($targetEntity) &&
                        !$uow->isScheduledForDelete($targetEntity) &&
                        !$uow->isScheduledForInsert($targetEntity)
                    ) {
                        $uow->persist($targetEntity);
                    }
                }
            }
        }
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $metadata = $this->getMetadata($args->getObject());
        if ($metadata !== null) {
            foreach ($metadata->getReferences() as $reference) {
                if ($reference->hasCascade(Reference::CASCADE_REMOVE)) {
                    $propertyAccessor = PropertyAccess::createPropertyAccessor();
                    $targetEntity = $propertyAccessor->getValue($args->getObject(), $reference->getProperty());
                    if ($targetEntity !== null) {
                        $args->getObjectManager()->remove($targetEntity);
                    }
                }
            }
        }
    }

    public function preFlush(PreFlushEventArgs $args): void
    {
        $uow = $args->getObjectManager()->getUnitOfWork();
        $this->persistEntities($uow, $args->getObjectManager());
    }

    /**
     * Check the identity map for entities with containing new references and persist them if cascade persists and the
     * referenced entity is not persists or removed yet. This case happen if an entity received from a repository,
     * and apply an entity as reference afterward, so a persist call was never triggered at this point
     */
    public function persistEntities(UnitOfWork $uow, ObjectManager $em): void
    {
        $identityMap = $uow->getIdentityMap();

        foreach ($this->getAllMetadata() as $metadata) {
            if (isset($identityMap[$metadata->getClassName()])) {
                foreach ($identityMap[$metadata->getClassName()] as $entity) {
                    foreach ($metadata->getReferences() as $reference) {
                        if ($reference->hasCascade(Reference::CASCADE_PERSIST)) {
                            $propertyAccessor = PropertyAccess::createPropertyAccessor();
                            $targetEntity = $propertyAccessor->getValue($entity, $reference->getProperty());
                            if ($targetEntity !== null &&
                                !$uow->isInIdentityMap($targetEntity) &&
                                !$uow->isScheduledForDelete($targetEntity) &&
                                !$uow->isScheduledForInsert($targetEntity)
                            ) {
                                $em->persist($targetEntity);
                            }
                        }
                    }
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        // Check if entity is not up-to-date and execute changes
        $changes = [];

        $uow = $args->getObjectManager()->getUnitOfWork();
        $result = $uow->getIdentityMap();

        foreach ($this->getAllMetadata() as $metadata) {
            if (isset($result[$metadata->getClassName()])) {
                foreach ($result[$metadata->getClassName()] as $entity) {
                    foreach ($metadata->getReferences() as $reference) {
                        $entityChanges = $this->calculateChanges($reference, $entity);
                        foreach ($entityChanges as $entityChange) {
                            $changes[] = $entityChange;
                        }
                    }
                }
            }
        }

        if (count($changes)) {
            $this->executeChanges($changes, $args->getObjectManager());
        }
    }

    /**
     * @return ReferenceChange[]
     */
    private function calculateChanges(Reference $reference, object $entity): array
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

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($changes as $change) {
            $query = $em->createQueryBuilder()
                ->update($this->getClass($change->getEntity()), 'e')
                ->set(sprintf('e.%s', $change->getProperty()), ':value')
                ->where('e.id = :id')
                ->setParameter('value', $change->getValue())
                ->setParameter('id', $propertyAccessor->getValue($change->getEntity(), 'id'))
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
    }
}
