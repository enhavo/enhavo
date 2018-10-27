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
        $template = $this->getOption('template', $parameters, 'EnhavoAppBundle:Block:table.html.twig');

        $filters = $this->getFilters($parameters);

        return $this->renderTemplate($template, [
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

    private function getFiltersFromRoute($route)
    {
        $route = $this->container->get('router')->getRouteCollection()->get($route);
        $sylius = $route->getDefault('_sylius');
        if(is_array($sylius)&& isset($sylius['filters'])) {
            return $this->getFilters($sylius['filters']);
        }
        return [];
    }

    private function calcFilterRowSize($filters)
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

    private function convertToFilterRows($filters)
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

    private function getFilters(array $parameters)
    {
        $filterFactory = $this->container->get('enhavo_app.factory.filter');

        $tableRoute = $this->getRequiredOption('table_route', $parameters);
        if(!isset($parameters['filters'])) {
            $filterData = $filterFactory->createFiltersFromRoute($tableRoute);
        } else {
            $filterData = $filterFactory->createFilters($this->getOption('filters', $parameters, []));
        }

        $authorizationChecker = $this->container->get('security.authorization_checker');
        foreach($filterData as $name => $filter) {
            if($filter->getPermission() && !$authorizationChecker->isGranted($filter->getPermission())) {
                unset($filterData[$name]);
            }
        }

        return $filterData;
    }

    public function getType()
    {
        return 'table';
    }
}