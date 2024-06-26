<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BetweenFilterType extends AbstractFilterType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
        $data->set('labelFrom', $this->translator->trans($options['label_from'], [], $options['translation_domain']));
        $data->set('labelTo', $this->translator->trans($options['label_to'], [], $options['translation_domain']));
        $data->set('initialValue', $this->getInitialValue($options));
    }

    public function getInitialValue($options): array
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

    private function buildFromQuery(FilterQuery$query, $options, $fromValue): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $fromValue, $propertyPath);
    }

    private function buildToQuery(FilterQuery $query, $options, $fromTo): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $fromTo, $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-between',
            'label_from' => 'filter.between.label.from',
            'label_to' => 'filter.between.label.to',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'between';
    }
}
