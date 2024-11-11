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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ListColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $propertyAccessor= new PropertyAccessor;

        $list = [];
        $itemProperty = $propertyAccessor->getValue($resource, $options['property']);
        foreach ($itemProperty as $child) {
            if (isset($options['item_property'])) {
                $list[] = $propertyAccessor->getValue($child, $options['item_property']);
            } else {
                $list[] = $child;
            }
        }

        $data->set('value', implode($options['separator'], $list));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
            'separator' => ','
        ]);
        $resolver->setRequired(['property', 'item_property']);
    }

    public static function getName(): ?string
    {
        return 'list';
    }
}
