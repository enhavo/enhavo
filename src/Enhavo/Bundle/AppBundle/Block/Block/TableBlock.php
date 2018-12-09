<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Filter\FilterFactory;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TableBlock extends AbstractType implements BlockInterface
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var FilterFactory
     */
    private $filterFactory;

    /**
     * TableBlock constructor.
     *
     * @param AuthorizationChecker $authorizationChecker
     * @param FilterFactory $filterFactory
     */
    public function __construct(AuthorizationChecker $authorizationChecker, FilterFactory $filterFactory)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->filterFactory = $filterFactory;
    }

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
        $tableRoute = $this->getRequiredOption('table_route', $parameters);
        if(!isset($parameters['filters'])) {
            $filterData = $this->filterFactory->createFiltersFromRoute($tableRoute);
        } else {
            $filterData = $this->filterFactory->createFilters($this->getOption('filters', $parameters, []));
        }

        foreach($filterData as $name => $filter) {
            if($filter->getPermission() && !$this->authorizationChecker->isGranted($filter->getPermission())) {
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