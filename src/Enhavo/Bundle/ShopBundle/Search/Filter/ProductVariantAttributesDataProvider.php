<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantAttributesDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        return [];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }

    public function getType()
    {
        return 'product_variant_attributes';
    }
}
