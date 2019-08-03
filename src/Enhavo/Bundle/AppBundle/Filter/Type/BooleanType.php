<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = [
            'type' => $this->getType(),
            'key' => $name,
            'value' => null,
            'initialValue' => null,
            'component' => $options['component'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $options['property'];
        $value = (boolean)$value;
        if($value) {
            $equals = $options['equals'];
            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $equals);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'equals' => true,
            'component' => 'filter-boolean'
        ]);
    }

    public function getType()
    {
        return 'boolean';
    }
}
