<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Metadata\MetadataInterface;

/**
 * SortingManager.php
 *
 * @since 26/06/16
 * @author gseidel
 */
class SortingManager
{
    public function update(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource)
    {

    }

    public function move(RequestConfiguration $requestConfiguration, MetadataInterface $metadataInterface, $resource)
    {

    }


    public function moveToPageAction($configuration)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'sorting');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $parameters = $viewer->getParameters();
        if (isset($parameters['sorting'])) {
            $sorting = strtoupper($parameters['sorting']);
        } else {
            throw new InvalidConfigurationException('Incompatible viewer type "' . get_class($viewer) . '" for route type move_to_page: expected field "sorting" in viewer->getParameters()');
        }
        $property = $this->config->getSortablePosition();
        $paginate = $this->config->getPaginationMaxPerPage();
        if (!$property || !$paginate) {
            return new JsonResponse(array('success' => false));
        }

        $resource = $this->findOr404($request);
        $page = $request->get('page');
        $top = $request->get('top');

        /** @var EntityRepository $repository */
        $repository = $this->getRepository();

        $pageOffset = ($page - 1) * $paginate + ($top ? 0 : ($paginate -1));

        $target = $repository->createQueryBuilder('r')
            ->orderBy('r.' . $property, $sorting)
            ->setFirstResult($pageOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$target) {
            // Last element
            $target = $repository->createQueryBuilder('r')
                ->addOrderBy('r.' . $property, $sorting == 'ASC' ? 'DESC' : 'ASC')
                ->addOrderBy('r.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
        if (!$target) {
            return new JsonResponse(array('success' => false));
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
            $this->recalculateSortingProperty($property, $sorting);
            $targetValue = $accessor->getValue(
                $target,
                $property
            );
        }

        $this->moveToPosition($resource, $targetValue, $property, $sorting);

        return new JsonResponse(array('success' => true));
    }

    protected function moveToPosition($resource, $position, $property, $sorting)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        $accessor = PropertyAccess::createPropertyAccessor();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));

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
            ->orderBy('r.' . $property, $sorting)
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
            $manager->persist($object);
        }
        $accessor->setValue(
            $resource,
            $property,
            $position
        );
        $manager->flush();
    }

    protected function generateInitialSortingValue($resource, $sortingConfig)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        $accessor = PropertyAccess::createPropertyAccessor();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));
        $property = $sortingConfig['position'];

        if ($sortingConfig['initial'] == 'min') {
            // Value is 0, but we need to move all other elements one up
            $existingResources = $repository->findAll();
            if ($existingResources) {
                foreach($existingResources as $existingResource) {
                    $value = $accessor->getValue(
                        $existingResource,
                        $property
                    );
                    $accessor->setValue(
                        $existingResource,
                        $property,
                        $value + 1
                    );
                    $manager->persist($existingResource);
                }
                $manager->flush();
            }
            $newValue = 0;
        } else {
            // Initial value is maximum of other elements + 1
            $maxResource = $repository->createQueryBuilder('r')
                ->orderBy('r.' . $property, 'DESC')
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
    }

    protected function recalculateSortingProperty($property, $sorting)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));
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
            $manager->persist($resource);
        }
        $manager->flush();
    }

    public function getSorting()
    {
        $sorting = $this->getConfig()->get('sorting');

        if (!$sorting or !is_array($sorting)) {
            $sorting = array();
        }

        if (!isset($sorting['sortable'])) {
            $sorting['sortable'] = false;
        }
        if (!isset($sorting['position'])) {
            $sorting['position'] = 'position';
        }
        if (!isset($sorting['initial'])) {
            $sorting['initial'] = 'max';
        }
        if (strtoupper($sorting['initial']) == 'MIN') {
            $sorting['initial'] = 'min';
        } elseif (strtoupper($sorting['initial']) == 'MAX') {
            $sorting['initial'] = 'max';
        } else {
            throw new InvalidConfigurationException('Invalid configuration value for _viewer.sorting.initial, expecting "min" or "max", got "' . $sorting['initial'] . '"');
        }

        return $sorting;
    }
}