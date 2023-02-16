<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\Util;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class AssociationFinder
{
    private array $incomingAssociationMapCache = [];
    private array $outgoingAssociationMapCache = [];

    /**
     * DoctrineAssociationFinder constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Finds all ORM associations to a doctrine managed entity.
     *
     * The optional parameter $entityClassName can be set to the fully qualified name of the class or interface used in
     * ORM mapping for $entity. Parent classes and interfaces include mappings to child classes, but not the other way around.
     * Defaults to the result of get_class($entity) if not provided.
     *
     * @param object $entity The entity to find associations to
     * @param string|null $entityClassName (Optional) Fully qualified class name of $entity
     * @param string[] $excludeClasses (Optional) Array of fully qualified class names of classes to exclude in search
     * @return array Array of entities that have an association to $entity.
     * @throws \Exception
     */
    public function findAssociationsTo(object $entity, ?string $entityClassName = null, array $excludeClasses = []): array
    {
        if ($entityClassName === null) {
            $entityClassName = get_class($entity);
        }
        if (!($entity instanceof $entityClassName)) {
            throw new \Exception('Error: Parameter $entityClassName must be the fully qualified class name of $entity, one of its parent classes or interfaces, or null.');
        }

        $results = [];
        foreach($this->getIncomingAssociationMap($entityClassName, $excludeClasses) as $association) {
            $queryBuilder = $this->entityManager->createQueryBuilder();
            $queryBuilder->select('o')
                ->from($association->getClass(), 'o');
            if ($association->isSingleValued()) {
                $queryBuilder->andWhere('o.' . $association->getField() . ' = :entity');
            } else {
                $queryBuilder
                    ->innerJoin('o.' . $association->getField(), 'j')
                    ->andWhere('j = :entity');
            }
            $queryBuilder->setParameter('entity', $entity);
            $classResult = $queryBuilder->getQuery()->getResult();
            if (count($classResult) > 0) {
                foreach($classResult as $row) {
                    $results []= $row;
                }
            }
        }
        return $results;
    }

    /**
     * Gets an array of Metadata about ORM associations to target class.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param string[] $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     * @return AssociationNode[]
     */
    public function getIncomingAssociationMap(string $targetClass, array $excludedClasses = []): array
    {
        $cacheKey = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        if (!isset($this->incomingAssociationMapCache[$cacheKey])) {
            $this->buildIncomingAssociationMapCache($targetClass, $excludedClasses);
        }
        return $this->incomingAssociationMapCache[$cacheKey];
    }

    /**
     * Creates an array of Metadata about ORM associations to target class and saves it in cache.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     */
    private function buildIncomingAssociationMapCache(string $targetClass, array $excludedClasses = []): void
    {
        $key = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        $this->incomingAssociationMapCache[$key] = [];

        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        /** @var ClassMetadata $classMetadata */
        foreach($metaData as $classMetadata) {
            if ($classMetadata->isMappedSuperclass) {
                continue;
            }
            $reflectionClass = $classMetadata->getReflectionClass();
            if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                continue;
            }
            $className = $classMetadata->getName();

            $isExcluded = false;
            foreach($excludedClasses as $excludedClass) {
                if (is_a($className, $excludedClass, true)) {
                    $isExcluded = true;
                    break;
                }
            }
            if ($isExcluded) {
                continue;
            }

            $associations = $classMetadata->getAssociationNames();
            foreach($associations as $associationName) {
                $associationClass = $classMetadata->getAssociationTargetClass($associationName);
                if (is_a($associationClass, $targetClass, true)) {
                    $this->incomingAssociationMapCache[$key] []= new AssociationNode($className, $associationName, $classMetadata->isSingleValuedAssociation($associationName));
                }
            }
        }
    }



    /**
     * Gets an array of Metadata about ORM associations outgoing from target class.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     * @return AssociationNode[]
     */
    public function getOutgoingAssociationMap(string $targetClass, array $excludedClasses = []): array
    {
        $cacheKey = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        if (!isset($this->outgoingAssociationMapCache[$cacheKey])) {
            $this->buildOutgoingAssociationMapCache($targetClass, $excludedClasses);
        }
        return $this->outgoingAssociationMapCache[$cacheKey];
    }

    private function buildOutgoingAssociationMapCache(string $targetClass, array $excludedClasses = []): void
    {
        $key = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        $this->outgoingAssociationMapCache[$key] = [];

        $classMetadata = $this->entityManager->getMetadataFactory()->getMetadataFor($targetClass);
        $associations = $classMetadata->getAssociationNames();
        foreach($associations as $associationName) {
            $associationClass = $classMetadata->getAssociationTargetClass($associationName);

            $isExcluded = false;
            foreach($excludedClasses as $excludedClass) {
                if (is_a($associationClass, $excludedClass, true)) {
                    $isExcluded = true;
                    break;
                }
            }
            if ($isExcluded) {
                continue;
            }

            $this->outgoingAssociationMapCache[$key] []= new AssociationNode($associationClass, $associationName, $classMetadata->isSingleValuedAssociation($associationName));
        }
    }

    /**
     * Generates cache key to for caching association maps. This key should be unique for any combination of the parameters $targetClass and $excludedClasses.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     * @return string
     */
    private function getAssociationMapCacheKey(string $targetClass, array $excludedClasses = []): string
    {
        return md5($targetClass . ':' . implode('.', $excludedClasses));
    }
}
