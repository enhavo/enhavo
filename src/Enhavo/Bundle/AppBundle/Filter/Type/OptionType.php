<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = [
            'type' => $this->getType(),
            'options' => $options['options'],
            'name' => $name,
            'component' => $options['component'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value == '') {
            return;
        }

        $possibleValues = $options['options'];
        $possibleValues = array_keys($possibleValues);
        $findPossibleValue = false;
        foreach($possibleValues as $possibleValue) {
            if($possibleValue == $value) {
                $findPossibleValue = true;
                break;
            }
        }

        if(!$findPossibleValue) {
            throw new FilterException('Value does not exists in options');
        }

        $property = $options['property'];
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => ''
        ]);
        $optionsResolver->setRequired(['options']);
    }

    public function getType()
    {
        return 'option';
    }
}