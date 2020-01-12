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

class BetweenFilterType extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = [
            'type' => $this->getType(),
            'key' => $name,
            'value' => [
                'from' => '',
                'to' => '',
            ],
            'initialValue' => null,
            'component' => $options['component'],
            'label' => [
                'from' => $options['label_from'] ?
                    $this->translator->trans($options['label_from'], [], $options['translation_domain']) :
                    $this->getLabel($options),
                'to' => $options['label_to'] ?
                    $this->translator->trans($options['label_to'], [], $options['translation_domain']) :
                    $this->getLabel($options),
            ],
        ];

        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $fromValue = $value['from'];
        $toValue = $value['to'];

        if(!empty($fromValue) && empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
        } elseif(empty($fromValue) && !empty($toValue)) {
            $this->buildToQuery($query, $options, $toValue);
        } elseif(!empty($fromValue) && !empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
            $this->buildToQuery($query, $options, $toValue);
        }
    }

    protected function buildFromQuery(FilterQuery$query, $options, $fromValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $fromValue, $propertyPath);
    }

    protected function buildToQuery(FilterQuery $query, $options, $fromTo)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $fromTo, $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => 'filter-between',
            'label_from' => 'filter.between.label.from',
            'label_to' => 'filter.between.label.to',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => null,
        ]);
    }

    public function getType()
    {
        return 'between';
    }
}
