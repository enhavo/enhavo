<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\Util;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;

class ReferenceFinder
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    private $referenceMapCache = [];

    /**
     * DoctrineReferenceFinder constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Finds all ORM references to a doctrine managed entity.
     *
     * The optional parameter $entityClassName can be set to the fully qualified name of the class or interface used in
     * ORM mapping for $entity. Parent classes and interfaces include mappings to child classes, but not the other way around.
     * Defaults to the result of get_class($entity) if not provided.
     *
     * @param object $entity The entity to find references to
     * @param string|null $entityClassName (Optional) Fully qualified class name of $entity
     * @param string[] $excludeClasses (Optional) Array of fully qualified class names of classes to exclude in search
     * @return array Array of entities that have a reference to $entity.
     * @throws \Exception
     */
    public function findReferencesTo($entity, $entityClassName = null, $excludeClasses = [])
    {
        if ($entityClassName === null) {
            $entityClassName = get_class($entity);
        }
        if (!($entity instanceof $entityClassName)) {
            throw new \Exception('Error: Parameter $entityClassName must be the fully qualified class name of $entity, one of its parent classes or interfaces, or null.');
        }

        $results = [];
        foreach($this->getReferenceMap($entityClassName, $excludeClasses) as $reference) {
            $queryBuilder = $this->entityManager->createQueryBuilder();
            $queryBuilder->select('o')
                ->from($reference['class'], 'o');
            if ($reference['singleValued']) {
                $queryBuilder->andWhere('o.' . $reference['field'] . ' = :entity');
            } else {
                $queryBuilder
                    ->innerJoin('o.' . $reference['field'], 'j')
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
     * Gets an array of Metadata about ORM references to target class.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param string[] $excludedClasses Optional array of fully qualified class names of classes to exclude in reference map
     * @return array
     */
    private function getReferenceMap($targetClass, $excludedClasses = [])
    {
        $cacheKey = $this->getReferenceMapCacheKey($targetClass, $excludedClasses);
        if (!isset($this->referenceMapCache[$cacheKey])) {
            $this->buildReferenceMapCache($targetClass, $excludedClasses);
        }
        return $this->referenceMapCache[$cacheKey];
    }

    /**
     * Generates cache key to for caching reference maps. This key should be unique for any combination of the parameters $targetClass and $excludedClasses.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in reference map
     * @return string
     */
    private function getReferenceMapCacheKey($targetClass, $excludedClasses = [])
    {
        return md5($targetClass . ':' . implode('.', $excludedClasses));
    }

    /**
     * Creates an array of Metadata about ORM references to target class and saves it in cache.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in reference map
     */
    private function buildReferenceMapCache($targetClass, $excludedClasses = [])
    {
        $key = $this->getReferenceMapCacheKey($targetClass, $excludedClasses);
        $this->referenceMapCache[$key] = [];

        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        /** @var ClassMetadata $classMetadata */
        foreach($metaData as $classMetadata) {
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
                    $this->referenceMapCache[$key] []= [
                        'class' => $className,
                        'field' => $associationName,
                        'singleValued' => $classMetadata->isSingleValuedAssociation($associationName)
                    ];
                }
            }
        }
    }
}
