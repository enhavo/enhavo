<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class DateColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor= new PropertyAccessor;
        $value = $propertyAccessor->getValue($resource, $options['property']);

        if ($value instanceof \DateTime) {
            $data->set('value', $value->format($options['format']));
        }
    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('property', $options['property']);
        $data->set('sortingProperty', $options['sortingProperty'] ? : $options['property']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => 'd.m.Y',
            'sortingProperty' => null,
            'component' => 'column-text'
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'date';
    }
}
