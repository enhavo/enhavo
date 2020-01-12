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

class AgeFilterType extends AbstractFilterType
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

    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-text'
        ]);
    }

    public function getType()
    {
        return 'age';
    }
}
