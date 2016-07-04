<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * SortingManager.php
 *
 * @since 26/06/16
 * @author gseidel
 */
class SortingManager
{
    const STRATEGY_DESC_FIRST = 'desc_first';
    const STRATEGY_DESC_LAST = 'desc_last';
    const STRATEGY_ASC_FIRST = 'asc_first';
    const STRATEGY_ASC_LAST = 'asc_last';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function update(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource, RepositoryInterface $repository)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $property = $requestConfiguration->getSortablePosition();
        $strategy = $requestConfiguration->getSortingStrategy();
        if ($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_ASC_FIRST) {
            // Value is 0, but we need to move all other elements one up
            $existingResources = $repository->findBy([], [
                $property => $strategy == self::STRATEGY_DESC_LAST ? 'desc' : 'asc'
            ]);
            if ($existingResources) {
                foreach($existingResources as $existingResource) {
                    if($resource === $existingResource) {
                        continue;
                    }
                    $value = $accessor->getValue(
                        $existingResource,
                        $property
                    );
                    $accessor->setValue(
                        $existingResource,
                        $property,
                        $value + 1
                    );
                }
            }
            $newValue = 0;
        } elseif($strategy == self::STRATEGY_DESC_FIRST || $strategy == self::STRATEGY_ASC_LAST) {
            // Initial value is maximum of other elements + 1
            $maxResource = $repository->createQueryBuilder('r')
                ->orderBy('r.' . $property, $strategy == self::STRATEGY_DESC_FIRST ? 'desc' : 'asc')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
            if (!$maxResource) {
                $newValue = 0;
            } else {
                $maxValue = $accessor->getValue(
                    $maxResource,
                    $property
                );
                $newValue = $maxValue + 1;
            }
        }
        $accessor->setValue(
            $resource,
            $property,
            $newValue
        );
        $this->em->flush();
    }

    public function moveAfter(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource, RepositoryInterface $repository, $target)
    {
        $property = $requestConfiguration->getSortablePosition();
        $targetResource = $repository->find($target);
        $accessor = PropertyAccess::createPropertyAccessor();
        $strategy = $requestConfiguration->getSortingStrategy();

        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );

        $targetValue = $accessor->getValue(
            $targetResource,
            $property
        );

        if ($resourceValue === null || $targetValue === null || $resourceValue == $targetValue) {
            $this->recalculateSortingProperty($property, $requestConfiguration->getSortingStrategy(), $repository);

            $resourceValue = $accessor->getValue(
                $resource,
                $property
            );

            $targetValue = $accessor->getValue(
                $targetResource,
                $property
            );
        }

        if (($strategy == self::STRATEGY_ASC_LAST || $strategy == self::STRATEGY_ASC_FIRST) && ($resourceValue > $targetValue)) {
            $targetPosition = $targetValue + 1;
        } elseif (($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST) && ($resourceValue < $targetValue)) {
            $targetPosition = $targetValue - 1;
        } else {
            $targetPosition = $targetValue;
        }

        $this->moveToPosition($resource, $targetPosition, $property, $strategy, $repository);
    }


    public function moveToPage(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource, RepositoryInterface $repository, $page, $top)
    {
        $property = $requestConfiguration->getSortablePosition();
        $strategy = $requestConfiguration->getSortingStrategy();
        $paginate = $requestConfiguration->getPaginationMaxPerPage();
        $sorting = $strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST ? 'desc' : 'asc';
        $pageOffset = ($page - 1) * $paginate + ($top ? 0 : ($paginate -1));

        $target = $repository->createQueryBuilder('r')
            ->orderBy('r.' . $property, $property, $sorting)
            ->setFirstResult($pageOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$target) {
            // Last element
            $target = $repository->createQueryBuilder('r')
                ->addOrderBy('r.' . $property, $sorting)
                ->addOrderBy('r.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );

        $targetValue = $accessor->getValue(
            $target,
            $property
        );

        if ($resourceValue === null || $targetValue === null || $resourceValue == $targetValue) {
            $this->recalculateSortingProperty($property, $sorting, $repository);
            $targetValue = $accessor->getValue(
                $target,
                $property
            );
        }

        $this->moveToPosition($resource, $targetValue, $property, $strategy, $repository);
    }

    protected function moveToPosition($resource, $position, $property, $strategy, $repository)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $resourceId = $accessor->getValue(
            $resource,
            'id'
        );
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );
        $max = max($resourceValue, $position);
        $min = min($resourceValue, $position);
        $movement = ($resourceValue < $position) ? -1 : +1;

        $toMove = $repository->createQueryBuilder('r')
            ->where('r.' . $property . ' <= :max')
            ->andWhere('r.' . $property . ' >= :min')
            ->setParameter('max', $max)
            ->setParameter('min', $min)
            ->orderBy('r.' . $property, $strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST ? 'desc' : 'asc')
            ->getQuery()
            ->getResult();

        foreach($toMove as $object) {

            $objectId = $accessor->getValue(
                $object,
                'id'
            );

            if ($objectId == $resourceId) {
                continue;
            }

            $objectValue = $accessor->getValue(
                $object,
                $property
            );

            $objectValue += $movement;

            $accessor->setValue(
                $object,
                $property,
                $objectValue
            );
        }

        $accessor->setValue(
            $resource,
            $property,
            $position
        );

        $this->em->flush();
    }

    protected function recalculateSortingProperty($property, $sorting, $repository)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (!in_array($sorting, array('ASC', 'DESC'))) {
            $sorting = 'DESC';
        }

        $allEntities = $repository->findBy(array(), array($property => $sorting));
        if ($sorting == 'DESC') {
            $allEntities = array_reverse($allEntities);
        }
        $i = 0;

        foreach($allEntities as $resource) {
            $accessor->setValue(
                $resource,
                $property,
                $i++
            );
        }
        $this->em->flush();
    }
}