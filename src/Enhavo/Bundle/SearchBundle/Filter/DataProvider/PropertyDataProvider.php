<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:42
 */

namespace Enhavo\Bundle\SearchBundle\Filter\DataProvider;


use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        $value = $this->getProperty($resource, $options['property']);
        $data = new Data();
        $data->setValue($value);
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'property';
    }
}