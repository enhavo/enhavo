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

class PropertyColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor= new PropertyAccessor();
        $value = $propertyAccessor->getValue($resource, $options['property']);
        $data->set('value', $value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
            'sortingProperty' => null,
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'property';
    }
}
