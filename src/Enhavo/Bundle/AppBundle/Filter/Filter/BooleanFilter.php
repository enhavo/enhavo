<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;

class BooleanFilter extends AbstractFilter
{
    public function render($options, $value)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Filter:boolean.html.twig');

        return $this->renderTemplate($template, [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
            'name' => $this->getRequiredOption('name', $options),
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $this->getRequiredOption('property', $options);

        $value = (boolean)$value;
        if($value) {
            $equals = $this->getRequiredOption('equals', $options);
            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $equals);
        }
    }

    public function getType()
    {
        return 'boolean';
    }
}