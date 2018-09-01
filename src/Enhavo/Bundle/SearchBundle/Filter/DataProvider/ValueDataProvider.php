<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 21:41
 */

namespace Enhavo\Bundle\SearchBundle\Filter\DataProvider;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        $data = new Data();
        $data->setValue($options['value']);
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired(['value']);
    }

    public function getType()
    {
        return 'value';
    }
}