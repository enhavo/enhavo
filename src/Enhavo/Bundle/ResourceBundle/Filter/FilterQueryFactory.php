<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Column\Column;

class FilterQueryFactory
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @param Filter[] $filters
     * @param Column[] $columns
     */
    public function create($class, array $filters = [], array $filterValues = [], array $columns = [], array $sortingValues = [], $criteria = [], $sorting = [], $paginated = false): FilterQuery
    {
        $filterQuery = new FilterQuery($this->em, $class);

        foreach ($criteria as $property => $value) {
            if (is_array($value)) {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_IN, $value);
            } else {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
            }
        }

        foreach ($filters as $key => $filter) {
            if (array_key_exists($key, $filterValues)) {
                $filter->setFilterValue($filterValues[$key]);
            }
            $filter->buildQuery($filterQuery);
        }

        foreach ($columns as $key => $column) {
            if (array_key_exists($key, $sortingValues)) {
                $column->buildSortingQuery($filterQuery, $sortingValues[$key]);
            }
        }

        foreach ($sorting as $property => $direction) {
            $filterQuery->addOrderBy($property, $direction);
        }

        $filterQuery->setHydrate(FilterQuery::HYDRATE_OBJECT);
        $filterQuery->setPaginated($paginated);

        return $filterQuery;
    }
}
