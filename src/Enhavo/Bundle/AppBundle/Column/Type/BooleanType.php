<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $property = $this->getProperty($resource, $options['property']);
        return (boolean) $property;
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'property' => $options['property'],
            'sortingProperty' => ($options['sortingProperty'] ? $options['sortingProperty'] : $options['property'])
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'format' => 'd.m.Y H:i',
            'sortingProperty' => null,
            'component' => 'column-boolean'
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'boolean';
    }
}
