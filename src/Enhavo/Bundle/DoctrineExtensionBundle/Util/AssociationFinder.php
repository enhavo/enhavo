<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\Util;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;

class AssociationFinder
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var array */
    private $associationMapCache = [];

    /**
     * DoctrineAssociationFinder constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
     * @return array Array of entities that have a association to $entity.
     * @throws \Exception
     */
    public function findAssociationsTo($entity, $entityClassName = null, $excludeClasses = [])
    {
        if ($entityClassName === null) {
            $entityClassName = get_class($entity);
        }
        if (!($entity instanceof $entityClassName)) {
            throw new \Exception('Error: Parameter $entityClassName must be the fully qualified class name of $entity, one of its parent classes or interfaces, or null.');
        }

        $results = [];
        foreach($this->getAssociationMap($entityClassName, $excludeClasses) as $association) {
            $queryBuilder = $this->em->createQueryBuilder();
            $queryBuilder->select('o')
                ->from($association['class'], 'o');
            if ($association['singleValued']) {
                $queryBuilder->andWhere('o.' . $association['field'] . ' = :entity');
            } else {
                $queryBuilder
                    ->innerJoin('o.' . $association['field'], 'j')
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
     * @return array
     */
    private function getAssociationMap($targetClass, $excludedClasses = [])
    {
        $cacheKey = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        if (!isset($this->associationMapCache[$cacheKey])) {
            $this->buildAssociationMapCache($targetClass, $excludedClasses);
        }
        return $this->associationMapCache[$cacheKey];
    }

    /**
     * Generates cache key to for caching association maps. This key should be unique for any combination of the parameters $targetClass and $excludedClasses.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     * @return string
     */
    private function getAssociationMapCacheKey($targetClass, $excludedClasses = [])
    {
        return md5($targetClass . ':' . implode('.', $excludedClasses));
    }

    /**
     * Creates an array of Metadata about ORM associations to target class and saves it in cache.
     *
     * @param string $targetClass Fully qualified class name of target class
     * @param array $excludedClasses Optional array of fully qualified class names of classes to exclude in association map
     */
    private function buildAssociationMapCache($targetClass, $excludedClasses = [])
    {
        $key = $this->getAssociationMapCacheKey($targetClass, $excludedClasses);
        $this->associationMapCache[$key] = [];

        $metaData = $this->em->getMetadataFactory()->getAllMetadata();
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
                    $this->associationMapCache[$key] []= [
                        'class' => $className,
                        'field' => $associationName,
                        'singleValued' => $classMetadata->isSingleValuedAssociation($associationName)
                    ];
                }
            }
        }
    }
}
