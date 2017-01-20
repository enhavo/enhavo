<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class TableBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        $translationDomain = $this->getOption('translationDomain', $parameters, null);
        $tableRoute = $this->getRequiredOption('table_route', $parameters);

        if(!isset($parameters['filters'])) {
            $filters = $this->getFiltersFromRoute($tableRoute, $translationDomain);
        } else {
            $filters = $this->getFilters($this->getOption('filters', $parameters, []), $translationDomain);
        }

        return $this->renderTemplate('EnhavoAppBundle:Block:table.html.twig', [
            'app' => $this->getOption('app', $parameters, 'app/Block/Table'),
            'table_route' => $tableRoute,
            'table_route_parameters' => $this->getOption('table_route_parameters', $parameters, null),
            'update_route_parameters' => $this->getOption('update_route_parameters', $parameters, null),
            'update_route' => $this->getOption('update_route', $parameters, null),
            'translationDomain' => $translationDomain,
            'filters' => $this->convertToFilterRows($filters),
            'filterRowSize' => $this->calcFilterRowSize($filters)
        ]);
    }

    protected function getFiltersFromRoute($route, $translationDomain)
    {
        $route = $this->container->get('router')->getRouteCollection()->get($route);
        $sylius = $route->getDefault('_sylius');
        if(is_array($sylius)&& isset($sylius['filters'])) {
            return $this->getFilters($sylius['filters'], $translationDomain);
        }
        return [];
    }

    protected function calcFilterRowSize($filters)
    {
        $amount = count($filters);
        if($amount == 1) {
            return 12;
        }
        if($amount == 2) {
            return 6;
        }
        if($amount == 3) {
            return 4;
        }
        return 3;
    }

    protected function convertToFilterRows($filters)
    {
        $rows = [];
        $i = 0;
        $index = 0;
        foreach($filters as $filter) {
            if($i === 0) {
                $rows[$index] = [];
            }

            $rows[$index][] = $filter;

            if($i == 3) {
                $i = 0;
                $index++;
            } else {
                $i++;
            }
        }
        return $rows;
    }

    protected function getFilters(array $filters, $translationDomain = null)
    {
        foreach($filters as $name => &$options) {
            $options['value'] = '';
            $options['name'] = $name;

        }

        foreach($filters as $name => &$options) {
            if(!array_key_exists('translationDomain', $options)) {
                $options['translationDomain'] = $translationDomain;
            }
        }

        return $filters;
    }

    public function getType()
    {
        return 'table';
    }
}