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

class ListType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $list = [];

        $itemProperty = $this->getProperty($resource, $options['property']);
        foreach($itemProperty as $child) {
            if(isset($options['item_property'])) {
                $list[] = $this->getProperty($child, $options['item_property']);
            } else {
                $list[] = $child;
            }
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
            'component' => '',
            'separator' => ','
        ]);
        $resolver->setRequired(['property', 'item_property']);
    }

    public function getType()
    {
        return 'list';
    }
}
