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

use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgeFilterType extends AbstractFilterType
{
    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        $fromValue = $value['from'] ?? null;
        $toValue = $value['to'] ?? null;

        if (!empty($fromValue) && empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
        } elseif (empty($fromValue) && !empty($toValue)) {
            $this->buildToQuery($query, $options, $toValue);
        } elseif (!empty($fromValue) && !empty($toValue)) {
            $this->buildFromQuery($query, $options, $fromValue);
            $this->buildToQuery($query, $options, $toValue);
        }
    }

    private function buildFromQuery(FilterQuery $query, $options, $fromValue): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', intval($fromValue)));

        $query->addWhere($property, FilterQuery::OPERATOR_LESS_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    private function buildToQuery(FilterQuery $query, $options, $fromTo): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $date = new \DateTime();
        $date->modify(sprintf('-%s year', intval($fromTo + 1)));

        $query->addWhere($property, FilterQuery::OPERATOR_GREATER_EQUAL, $date->format('Y-m-d'), $propertyPath);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label_from' => 'filter.age.label.from',
            'label_to' => 'filter.age.label.to',
        ]);

        $resolver->setRequired('property');
    }

    public static function getParentType(): ?string
    {
        return BetweenFilterType::class;
    }

    public static function getName(): ?string
    {
        return 'age';
    }
}
