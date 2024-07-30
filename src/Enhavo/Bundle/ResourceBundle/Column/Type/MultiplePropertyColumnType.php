<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:11
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MultiplePropertyColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor= new PropertyAccessor();

        $list = [];
        foreach($options['properties'] as $property) {
            $list[] = $propertyAccessor->getValue($resource, $property);
        }

        $data->set('data', implode($options['separator'], $list));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
            'separator' => ','
        ]);
        $resolver->setRequired(['properties']);
    }

    public static function getName(): ?string
    {
        return 'multiple_property';
    }

}
