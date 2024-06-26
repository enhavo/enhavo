<?php
/**
 * FilterQuery.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\Request;

class FilterQueryBuilder
{
    /**
     * @var FilterFactory
     */
    private $factory;

    /**
     * @var FilterQueryFactory
     */
    private $filterQueryFactory;

    /**
     * FilterQueryBuilder constructor.
     *
     * @param FilterFactory $filterFactory
     * @param FilterQueryFactory $filterQueryFactory
     */
    public function __construct(FilterFactory $filterFactory, FilterQueryFactory $filterQueryFactory)
    {
        $this->factory = $filterFactory;
        $this->filterQueryFactory = $filterQueryFactory;
    }

    public function buildQueryFromRequestConfiguration(RequestConfiguration $requestConfiguration)
    {
        $request = $requestConfiguration->getRequest();
        $filters = $requestConfiguration->getFilters();
        $criteria = $requestConfiguration->getCriteria();
        $sorting = $requestConfiguration->getSorting();
        $paginated = $requestConfiguration->isPaginated();

        return $this->buildQueryFromRequest($request, $filters, $requestConfiguration->getMetadata()->getClass('model'), $sorting, $criteria, $paginated);
    }

    public function buildQueryFromRequest(Request $request, $filters, $class, $sorting = [], $criteria = [], $paginated = true)
    {
        $filterQuery = $this->filterQueryFactory->create($class);

        foreach($sorting as $property => $order) {
            $propertyPath = explode('.', $property);
            $topProperty = array_pop($propertyPath);
            $filterQuery->addOrderBy($topProperty, $order, $propertyPath);
        }

        foreach($criteria as $property => $value) {
            if (is_array($value)) {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_IN, $value);
            } else {
                $filterQuery->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
            }
        }

        $filterValues = $this->getRequestFilterValues($request);
        $filterData = $this->factory->createFilters($filters);
        foreach($filterData as $filter) {
            $filter->buildQuery($filterQuery, $this->getValue($filter->getName(), $filterValues));
        }

        $filterQuery->setHydrate($request->get('hydrate', FilterQuery::HYDRATE_OBJECT));
        $filterQuery->setPaginated($paginated);

        return $filterQuery;
    }

    private function getRequestFilterValues(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if(isset($data['filters'])) {
            return $data['filters'];
        }
        return [];
    }

    private function getValue($name, $filterValues)
    {
        foreach($filterValues as $data) {
            if(isset($data['name']) && isset($data['value']) && $data['name'] == $name) {
                return $data['value'];
            }
        }
        return null;
    }
}
