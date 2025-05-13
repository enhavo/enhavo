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

class TextType extends AbstractFilterType
{
    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        $propertyPath = explode('.', $options['property']);
        $property = array_pop($propertyPath);

        $operator = $options['operator'];
        if (null !== $value && '' !== trim($value)) {
            $query->addWhere($property, $operator, $value, $propertyPath);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-text',
            'model' => 'TextFilter',
        ]);

        $resolver->setRequired('property');
    }

    public static function getName(): ?string
    {
        return 'text';
    }
}
