<?php
/**
 * DateBetweenFilter.php
 *
 * @since 16/01/20
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @property BetweenFilterType $parent
 */
class DateBetweenFilterType extends AbstractFilterType
{
    public function createViewData($options, Data $data): void
    {
        $data['locale'] = $options['locale'];
        $data['format'] = $options['format'];
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        $fromValue = $value['from'] ?? null;
        $toValue = $value['to'] ?? null;

        if (!empty($fromValue) && empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
        } elseif(empty($fromValue) && !empty($toValue)) {
            $this->buildToQuery($query, $options, $toValue);
        } elseif(!empty($fromValue) && !empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
            $this->buildToQuery($query, $options, $toValue);
        }
    }

    private function getInitialValue($options): mixed
    {
        $initialValue = $this->parent->getInitialValue($options);
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

    private function buildFromQuery(FilterQuery $query, $options, $fromValue): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify($fromValue);

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    private function buildToQuery(FilterQuery $query, $options, $toValue): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify($toValue);

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-date-between',
            'label_from' => 'filter.date_between.label.from',
            'label_to' => 'filter.date_between.label.to',
            'locale' => 'de',
            'format' => 'dd.MM.yyyy'
        ]);
    }

    public static function getParentType(): ?string
    {
        return BetweenFilterType::class;
    }

    public static function getName(): ?string
    {
        return 'date_between';
    }
}
