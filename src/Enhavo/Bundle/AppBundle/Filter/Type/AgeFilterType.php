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
        $fromValue = intval($value['from']);
        $toValue = intval($value['to']);

        if(!empty($fromValue) && empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
        } elseif(empty($fromValue) && !empty($toValue)) {
            $this->buildToQuery($query, $options, $toValue);
        } elseif(!empty($fromValue) && !empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
            $this->buildToQuery($query, $options, $toValue);
        }
    }

    private function buildFromQuery(FilterQuery$query, $options, $fromValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', $fromValue));

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    private function buildToQuery(FilterQuery $query, $options, $fromTo)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', $fromTo));

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-between',
            'label_from' => 'filter.age.label.from',
            'label_to' => 'filter.age.label.to',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => null
        ]);
    }

    public function getType()
    {
        return 'age';
    }
}
