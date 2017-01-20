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
        $filters = $this->getFilters($this->getOption('filters', $parameters, []), $translationDomain);

        return $this->renderTemplate('EnhavoAppBundle:Block:table.html.twig', [
            'app' => $this->getOption('app', $parameters, 'app/Block/Table'),
            'table_route' => $this->getOption('table_route', $parameters, null),
            'table_route_parameters' => $this->getOption('table_route_parameters', $parameters, null),
            'update_route_parameters' => $this->getOption('update_route_parameters', $parameters, null),
            'update_route' => $this->getOption('update_route', $parameters, null),
            'translationDomain' => $translationDomain,
            'filters' => $filters
        ]);
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