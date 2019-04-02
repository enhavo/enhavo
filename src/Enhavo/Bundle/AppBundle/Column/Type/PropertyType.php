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

class PropertyType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $resource =  $this->getProperty($resource, $options['property']);
        return $resource;
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
            'component' => 'column-property',
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'property';
    }
}
