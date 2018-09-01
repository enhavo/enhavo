<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.09.18
 * Time: 16:37
 */

namespace Enhavo\Bundle\SearchBundle\Filter\DataProvider;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        $value = $this->getProperty($resource, $options['property']);
        if($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        } else {
            $value = null;
        }

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
        return 'date';
    }
}