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
        $data = parent::createViewData($options, $name);

        $choices = $this->formatChoices($options);
        $this->validateInitialValue($options['initial_value'], $choices);

        $data = array_merge($data, [
            'choices' => $choices
        ]);

        return $data;
    }

    private function formatChoices($options)
    {
        $data = [];
        foreach($options['options'] as $value => $label) {
            $data[] = [
                'label' => $this->translator->trans($label, [], $options['translation_domain']),
                'code' => $value,
            ];
        }
        return $data;
    }

    private function validateInitialValue($value, $choices)
    {
        if ($value === null) {
            return;
        }
        foreach($choices as $choice) {
            if ($choice['code'] == $value) {
                return;
            }
        }

        throw new \InvalidArgumentException('Parameter "initial_value" must either be null or one of keys in parameter "options"');
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value === null || trim($value) === '') {
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

        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => 'filter-option'
        ]);
        $optionsResolver->setRequired(['options']);
    }

    public function getType()
    {
        return 'option';
    }
}
