<?php
/**
 * FilterQuery.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Exception\FilterException;
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

        return $this->buildQueryFromRequest($request, $filters, $sorting, $criteria);
    }

    public function buildQueryFromRequest(Request $request, $filters, $sorting = [], $criteria = [])
    {
        $filterQuery = $this->filterQueryFactory->create();

        foreach($sorting as $property => $order) {
            $filterQuery->addOrderBy($property, $order);
        }

        foreach($criteria as $property => $value) {
            $filterQuery->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
        }

        $filterValues = $this->getRequestFilterValues($request);
        $filterData = $this->factory->createFilters($filters);
        foreach($filterData as $filter) {
            $filter->buildQuery($filterQuery, $this->getValue($filter->getName(), $filterValues));
        }

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
