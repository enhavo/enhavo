<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:42
 */

namespace Enhavo\Bundle\SearchBundle\Filter\Data;


use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyData extends AbstractType
{
    public function createData($resource, $options)
    {
        $value = $this->getProperty($resource, $options['property']);
        $data = new Data();
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }

    public function getType()
    {
        return 'property';
    }
}