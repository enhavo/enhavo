<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Filter\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BooleanType extends AbstractFilterType
{
    public const VALUE_TRUE = 1;
    public const VALUE_FALSE = 2;

    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createViewData($options, Data $data): void
    {
        if (!$options['checkbox']) {
            if ('filter-boolean' === $data['component']) {
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
        }

        return null === $options['initial_value'] ? null : ($options['initial_value'] ? self::VALUE_TRUE : self::VALUE_FALSE);
    }

    private function getChoices($options): array
    {
        return [
            [
                'label' => $this->translator->trans($options['label_true'], [], $options['translation_domain']),
                'code' => self::VALUE_TRUE,
            ],
            [
                'label' => $this->translator->trans($options['label_false'], [], $options['translation_domain']),
                'code' => self::VALUE_FALSE,
            ],
        ];
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if (null === $value) {
            return;
        }
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        if ($options['checkbox']) {
            $boolValue = (bool) $value;
            if ($boolValue) {
                $equals = $options['equals'];
                $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $equals, $propertyPath);
            }
        } else {
            if (self::VALUE_TRUE == $value) {
                $boolValue = true;
            } elseif (self::VALUE_FALSE == $value) {
                $boolValue = false;
            } else {
                throw new FilterException('Value invalid, must be one of '.implode(',', [self::VALUE_TRUE, self::VALUE_FALSE]));
            }

            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $boolValue, $propertyPath);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'equals' => true,
            'component' => 'filter-checkbox',
            'model' => 'BooleanFilter',
            'checkbox' => true,
            'label_true' => 'filter.boolean.label_true',
            'label_false' => 'filter.boolean.label_false',
            'translation_domain' => 'EnhavoResourceBundle',
            'initial_value' => null,
        ]);

        $resolver->setRequired('property');
    }

    public static function getName(): ?string
    {
        return 'boolean';
    }
}
