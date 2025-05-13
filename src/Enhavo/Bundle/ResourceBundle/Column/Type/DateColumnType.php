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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class DateColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();
        $value = $propertyAccessor->getValue($resource, $options['property']);

        if ($value instanceof \DateTime) {
            $data->set('value', $value->format($options['format']));
        }
    }

    public function createColumnViewData(array $options, Data $data): void
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => 'd.m.Y',
            'component' => 'column-text',
            'model' => 'TextColumn',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'date';
    }
}
