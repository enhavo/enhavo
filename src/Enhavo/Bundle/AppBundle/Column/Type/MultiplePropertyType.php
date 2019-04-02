<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:11
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiplePropertyType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $list = [];
        foreach($options['properties'] as $property) {
            $list[] = $this->getProperty($resource, $property);
        }
        return implode($options['separator'], $list);
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [

        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-multiple-property',
            'separator' => ','
        ]);
        $resolver->setRequired(['properties']);
    }

    public function getType()
    {
        return 'multiple_property';
    }

}
