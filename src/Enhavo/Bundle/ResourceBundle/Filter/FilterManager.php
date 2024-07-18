<?php

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FilterManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $factory,
    )
    {
    }

    public function getFilters(array $configuration): array
    {
        $filters = [];
        foreach ($configuration as $key => $options) {
            /** @var Filter $filter */
            $filter = $this->factory->create($options, $key);

            if (!$filter->isEnabled()) {
                continue;
            }

            if ($filter->getPermission() !== null && !$this->checker->isGranted($filter->getPermission())) {
                continue;
            }

            $filters[] = $filter;
        }

        return $filters;
    }

    /**
     * @param Filter[] $filters
     */
    public function setFilterValues(Request $request, array $filters)
    {
        $filterValues = $this->getRequestFilterValues($request);
        foreach($filters as $key => $filter) {
            $filter->setFilterValue($this->getFilterValue($key, $filterValues));
        }
    }

    private function getRequestFilterValues(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['filters'])) {
            return $data['filters'];
        }
        return [];
    }

    private function getFilterValue($name, $filterValues)
    {
        foreach($filterValues as $data) {
            if(isset($data['name']) && isset($data['value']) && $data['name'] == $name) {
                return $data['value'];
            }
        }
        return null;
    }
}
