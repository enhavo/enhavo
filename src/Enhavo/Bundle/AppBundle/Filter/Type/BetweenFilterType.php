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
        $data = parent::createViewData($options, $name);

        $data = array_merge($data, [
            'label' => $this->getMainLabel($options),
            'labelFrom' => $this->getLabelFrom($options),
            'labelTo' => $this->getLabelTo($options),
        ]);

        return $data;
    }

    protected function getInitialValue($options)
    {
        $from = '';
        $to = '';
        if (is_array($options['initial_value'])) {
            if (count($options['initial_value']) != 2) {
                throw new \InvalidArgumentException('Parameter "initial_value" must either be null, a scalar or an array with two entries');
            }

            if (isset($options['initial_value']['from'])) {
                $from = $options['initial_value']['from'];
            } elseif (isset($options['initial_value'][0])) {
                $from = $options['initial_value'][0];
            }

            if (isset($options['initial_value']['to'])) {
                $to = $options['initial_value']['to'];
            } elseif (isset($options['initial_value'][1])) {
                $to = $options['initial_value'][1];
            }
        } else {
            $from = $options['initial_value'];
            $to = $options['initial_value'];
        }
        return [
            'from' => $from,
            'to' => $to
        ];
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        $fromValue = $value['from'] ?? null;
        $toValue = $value['to'] ?? null;

        if(!empty($fromValue) && empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
        } elseif(empty($fromValue) && !empty($toValue)) {
            $this->buildToQuery($query, $options, $toValue);
        } elseif(!empty($fromValue) && !empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
            $this->buildToQuery($query, $options, $toValue);
        }
    }

    protected function getMainLabel($options): string
    {
        return $options['label'] ?
            $this->translator->trans($options['label'], [], $options['translation_domain']) :
            $this->getLabelFrom($options);
    }

    protected function getLabelFrom($options): string
    {
        return $options['label_from'] ?
            $this->translator->trans($options['label_from'], [], $options['translation_domain']) :
            $this->getLabel($options);
    }

    protected function getLabelTo($options): string
    {
        return $options['label_to'] ?
            $this->translator->trans($options['label_to'], [], $options['translation_domain']) :
            $this->getLabel($options);
    }

    protected function buildFromQuery(FilterQuery$query, $options, $fromValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $fromValue, $propertyPath);
    }

    protected function buildToQuery(FilterQuery $query, $options, $fromTo)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $fromTo, $propertyPath);
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
