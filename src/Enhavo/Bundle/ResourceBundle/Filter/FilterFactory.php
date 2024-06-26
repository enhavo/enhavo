<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.10.18
 * Time: 10:50
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FilterFactory
{
    /**
     * @var CollectorInterface
     */
    private $collector;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(CollectorInterface $collector, RouterInterface $router)
    {
        $this->collector = $collector;
        $this->router = $router;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Filter
     */
    public function createFilter($name, $options)
    {
        $type = $options['type'];
        unset($options['type']);

        $filterType = $this->collector->getType($type);
        $filter = new Filter($filterType, $options, $name);
        return $filter;
    }

    /**
     * @param array $filters
     * @return Filter[]
     * @throws TypeMissingException
     */
    public function createFilters($filters)
    {
        $filterData = [];

        // check permission
        foreach($filters as $name => &$options) {
            if(!isset($options['type'])) {
                throw new TypeMissingException(sprintf('No type was set for filter "%s"', $name));
            }
            /** @var FilterTypeInterface $filter */
            $filter = $this->createFilter($name, $options);
            $filterData[$name] = $filter;
        }

        return $filterData;
    }

    public function createFiltersFromRequest(Request $request)
    {
        $sylius = $request->attributes->get('_sylius');
        if(is_array($sylius) && isset($sylius['filters'])) {
            return $this->createFilters($sylius['filters']);
        }
        return [];
    }

    public function createFiltersFromRoute($routeName)
    {
        $route = $this->router->getRouteCollection()->get($routeName);
        $sylius = $route->getDefault('_sylius');
        if(is_array($sylius) && isset($sylius['filters'])) {
            return $this->createFilters($sylius['filters']);
        }
        return [];
    }
}
