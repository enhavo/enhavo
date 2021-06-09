<?php
/**
 * DateBetweenFilter.php
 *
 * @since 16/01/20
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Type;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateBetweenFilterType extends BetweenFilterType
{
    public function createViewData($options, $name)
    {
        $data = parent::createViewData($options, $name);
        $data['locale'] = $options['locale'];
        $data['format'] = $options['format'];
        return $data;
    }

    protected function getInitialValue($options)
    {
        $initialValue = parent::getInitialValue($options);
        if ($initialValue['from']) {
            if (is_int($initialValue['from'])) {
                $initialValue['from'] = (new \DateTime())->setTimestamp($initialValue['from'])->format('c');
            } else {
                $initialValue['from'] = (new \DateTime($initialValue['from']))->format('c');
            }
        }
        if ($initialValue['to']) {
            if (is_int($initialValue['to'])) {
                $initialValue['to'] = (new \DateTime())->setTimestamp($initialValue['to'])->format('c');
            } else {
                $initialValue['to'] = (new \DateTime($initialValue['to']))->format('c');
            }
        }
        return $initialValue;
    }

    protected function buildFromQuery(FilterQuery $query, $options, $fromValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify($fromValue);

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    protected function buildToQuery(FilterQuery $query, $options, $toValue)
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify($toValue);

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => 'filter-date-between',
            'label_from' => 'filter.date_between.label.from',
            'label_to' => 'filter.date_between.label.to',
            'locale' => $this->container->get('enhavo_app.locale_resolver')->resolve(),
            'format' => 'dd.MM.yyyy'
        ]);
    }

    public function getType()
    {
        return 'date_between';
    }
}
