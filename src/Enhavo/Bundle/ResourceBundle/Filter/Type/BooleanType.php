<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BooleanType extends AbstractFilterType
{
    const VALUE_TRUE = 1;
    const VALUE_FALSE = 2;

    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        if (!$options['checkbox']) {
            if ($data['component'] === 'filter-boolean') {
                $data['component'] = 'filter-option';
            }
            $data['choices'] = $this->getChoices($options);
        }
        $data['initialValue'] = $this->getInitialValue($options);
    }

    private function getInitialValue($options)
    {
        if ($options['checkbox']) {
            return $options['initial_value'];
        } else {
            return $options['initial_value'] === null ? null : ($options['initial_value'] ? self::VALUE_TRUE : self::VALUE_FALSE);
        }
    }

    private function getChoices($options): array
    {
        return [
            [
                'label' => $this->translator->trans($options['label_true'], [], $options['translation_domain']),
                'code' => self::VALUE_TRUE
            ],
            [
                'label' => $this->translator->trans($options['label_false'], [], $options['translation_domain']),
                'code' => self::VALUE_FALSE
            ]
        ];
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if($value === null) {
            return;
        }
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        if ($options['checkbox']) {
            $boolValue = (boolean)$value;
            if($boolValue) {
                $equals = $options['equals'];
                $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $equals, $propertyPath);
            }
        } else {
            if ($value == self::VALUE_TRUE) {
                $boolValue = true;
            } elseif ($value == self::VALUE_FALSE) {
                $boolValue = false;
            } else {
                throw new FilterException('Value invalid, must be one of ' . implode(',', [self::VALUE_TRUE, self::VALUE_FALSE]));
            }

            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $boolValue, $propertyPath);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'equals' => true,
            'component' => 'filter-boolean',
            'checkbox' => true,
            'label_true' => 'filter.boolean.label_true',
            'label_false' => 'filter.boolean.label_false',
            'translation_domain' => 'EnhavoAppBundle'
        ]);
    }

    public static function getName(): ?string
    {
        return 'boolean';
    }
}
