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

class TextType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = [
            'type' => $this->getType(),
            'key' => $name,
            'value' => null,
            'component' => $options['component'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $property = $options['property'];
        $joinProperty = [];
        if(substr_count($property, '.') >= 1){
            $exploded = explode('.', $property);
            foreach ($exploded as $piece) {
                if(count($exploded) > 1){
                    $joinProperty[] = array_shift($exploded);
                } elseif (count($exploded) === 1) {
                    $property = array_shift($exploded);
                }
            }
        }
        
        $operator = $options['operator'];
        if($value) {
            $query->addWhere($property, $operator, $value, $joinProperty ? $joinProperty : null);
        }
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
        return 'text';
    }
}
