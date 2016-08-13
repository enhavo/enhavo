<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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

    public function initialize(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource, EntityRepository $repository)
    {
        if(!$requestConfiguration->isSortable()) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $property = $requestConfiguration->getSortablePosition();
        $strategy = $requestConfiguration->getSortingStrategy();

        if ($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_ASC_FIRST) {
            // target value is 0, and we need to move all other elements one up
            $existingResources = $repository->findAll();
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
        } else {
            // Initial value is maximum of other elements + 1
            $maxResource = $repository->createQueryBuilder('r')
                ->orderBy('r.' . $property, 'desc')
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

    /**
     * Moves resource directly behind the position of existing object with id $target
     *
     * @param RequestConfiguration $requestConfiguration
     * @param MetadataInterface $metadataInterface
     * @param $resource
     * @param RepositoryInterface $repository
     * @param int $target
     */
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
            // Errors in value consistency: recalculate all position values for this entity
            $this->recalculateSortingProperty($property, $repository);

            $resourceValue = $accessor->getValue(
                $resource,
                $property
            );
            $targetValue = $accessor->getValue(
                $targetResource,
                $property
            );
        }

        // Adjust target position based on the resources position relative to the targets position
        if (($strategy == self::STRATEGY_ASC_LAST || $strategy == self::STRATEGY_ASC_FIRST) && ($resourceValue > $targetValue)) {
            // Resource is below target
            // To get to position one below target, we don't need to move target, only get to position one below, which means to position + 1 in asc strategies.
            $targetPosition = $targetValue + 1;
        } elseif (($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST) && ($resourceValue < $targetValue)) {
            // Resource is below target
            // To get to position one below target, we don't need to move target, only get to position one below, which means to position - 1 in desc strategies.
            $targetPosition = $targetValue - 1;
        } else {
            // Resource is above target, we need to move target as well to get to the position one below
            $targetPosition = $targetValue;
        }

        // Execute movement
        $this->moveToPosition($resource, $targetPosition, $property, $strategy, $repository);
    }

    /**
     * Moves resource to the top or bottom of a pagination page.
     *
     * @param RequestConfiguration $requestConfiguration
     * @param MetadataInterface $metadataInterface
     * @param $resource
     * @param EntityRepository $repository
     * @param int $page
     * @param bool $top
     */
    public function moveToPage(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource, $repository, $page, $top)
    {
        $property = $requestConfiguration->getSortablePosition();
        $strategy = $requestConfiguration->getSortingStrategy();
        $paginate = $requestConfiguration->getPaginationMaxPerPage();
        $accessor = PropertyAccess::createPropertyAccessor();
        $sorting = ($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST) ? 'desc' : 'asc';
        $targetPosition = ($page - 1) * $paginate + ($top ? 0 : ($paginate -1));

        $target = $repository->createQueryBuilder('r')
            ->orderBy('r.' . $property, $sorting)
            ->setFirstResult($targetPosition)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$target) {
            // The target position is after the last element, get last element instead
            $target = $repository->createQueryBuilder('r')
                ->addOrderBy('r.' . $property, $sorting)
                ->addOrderBy('r.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );
        $targetValue = $accessor->getValue(
            $target,
            $property
        );

        if ($resourceValue === null || $targetValue === null || $resourceValue == $targetValue) {
            // Errors in value consistency: recalculate all position values for this entity
            $this->recalculateSortingProperty($property, $repository);
            $targetValue = $accessor->getValue(
                $target,
                $property
            );
        }

        // Execute movement
        $this->moveToPosition($resource, $targetValue, $property, $strategy, $repository);
    }

    /**
     * Moves every object between the resource and its target position 1 up/down and places the resource at its new position
     *
     * @param $resource
     * @param int $position
     * @param string $property
     * @param string $strategy
     * @param EntityRepository $repository
     */
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

        // Get all objects between current position and target position, including both
        $max = max($resourceValue, $position);
        $min = min($resourceValue, $position);
        $movement = ($resourceValue < $position) ? -1 : +1;

        $toMove = $repository->createQueryBuilder('r')
            ->where('r.' . $property . ' <= :max')
            ->andWhere('r.' . $property . ' >= :min')
            ->setParameter('max', $max)
            ->setParameter('min', $min)
            ->orderBy('r.' . $property, ($strategy == self::STRATEGY_DESC_LAST || $strategy == self::STRATEGY_DESC_FIRST) ? 'desc' : 'asc')
            ->getQuery()
            ->getResult();

        // Move all objects between 1 up or down
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

        // Move resource to target position
        $accessor->setValue(
            $resource,
            $property,
            $position
        );

        $this->em->flush();
    }

    /**
     * Should be run if there are null values or multiple objects with the same value in the sorting property,
     * for example after creating a new sorting property on an existing entity.
     * Gives all objects a distinct sorting position value.
     *
     * @param string $property
     * @param EntityRepository $repository
     */
    protected function recalculateSortingProperty($property, $repository)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        // Get all objects, preserve order where possible
        $allEntities = $repository->findBy(array(), array($property => 'ASC'));

        // Replace position value with distinct ascending number
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