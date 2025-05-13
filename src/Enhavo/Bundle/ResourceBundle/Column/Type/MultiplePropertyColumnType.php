<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MultiplePropertyColumnType extends AbstractColumnType
{
    public function buildSortingQuery($options, FilterQuery $query, string $direction): void
    {
        if ($options['sortable']) {
            foreach ($options['properties'] as $property) {
                $propertyPath = explode('.', $property);
                $topProperty = array_pop($propertyPath);
                $query->addOrderBy($topProperty, $direction, $propertyPath);
            }
        }
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $list = [];
        foreach ($options['properties'] as $property) {
            $list[] = $propertyAccessor->getValue($resource, $property);
        }

        $data->set('value', implode($options['separator'], $list));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
            'separator' => ',',
        ]);
        $resolver->setRequired(['properties']);
    }

    public static function getName(): ?string
    {
        return 'multiple_property';
    }
}
