<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.10.18
 * Time: 15:14
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;


use Doctrine\ORM\EntityManagerInterface;

class FilterQueryFactory
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function create($class, array $filters = [], $criteria = [], $sorting = [], $paginated = false)
    {
        $filterQuery = new FilterQuery($this->em, $class);

        foreach ($sorting as $property => $order) {
            $propertyPath = explode('.', $property);
            $topProperty = array_pop($propertyPath);
            $filterQuery->addOrderBy($topProperty, $order, $propertyPath);
        }

        foreach ($criteria as $property => $value) {
            if (is_array($value)) {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_IN, $value);
            } else {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
            }
        }

        foreach ($filters as $filter) {
            $filter->buildQuery($filterQuery);
        }

        $filterQuery->setHydrate(FilterQuery::HYDRATE_OBJECT);
        $filterQuery->setPaginated($paginated);

        return $filterQuery;
    }

}
