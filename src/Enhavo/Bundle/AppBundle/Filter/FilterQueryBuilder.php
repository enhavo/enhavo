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
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\HttpFoundation\Request;

class FilterQueryBuilder
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * AbstractRenderer constructor.
     *
     * @param CollectorInterface $collector
     */
    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
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
        $filterQuery = $this->createFilterQuery();

        foreach($sorting as $property => $order) {
            $filterQuery->addOrderBy($property, $order);
        }

        foreach($criteria as $property => $value) {
            $filterQuery->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
        }

        $requestFilters = $request->query->get('filters', null);
        if($requestFilters === null) {
            return $filterQuery;
        }

        $requestFilters = json_decode($requestFilters, true);
        if(!is_array($requestFilters)) {
            throw new FilterException('Filter was not a json array');
        }

        foreach($requestFilters as $filter) {
            $name = $filter['name'];
            $value = $filter['value'];
            if(!array_key_exists($name, $filters)) {
                throw new FilterException(sprintf('Filter was not defined'));
            }
            $filter = $filters[$name];
            if(!array_key_exists('type', $filter)) {
                throw new FilterException('Filter type was not defined');
            }
            $this->buildQuery($filter['type'], $value, $filter, $filterQuery);
        }

        return $filterQuery;
    }

    protected function buildQuery($type, $value, $options, FilterQuery $filterQuery)
    {
        /** @var FilterInterface $filter */
        $filter = $this->collector->getType($type);
        $filter->buildQuery($filterQuery, $options, $value);
    }

    protected function createFilterQuery()
    {
        return new FilterQuery();
    }
}