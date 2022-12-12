<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantAttributesDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        if ($resource instanceof ProductInterface) {
            $resource = $resource->getDefaultVariant();
        }

        if (!$resource instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_attributes" can only applied on ProductVariantInterface but "%s" given', get_class($resource)));
        }

        $return = [];
        foreach ($resource->getProduct()->getAttributes() as $attribute) {
            $data = new Data();
            $data->setKey('attribute-'.$attribute->getCode());
            $data->setValue($attribute->getValue());
            $return[] = $data;
        }

        return $return;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }

    public function getType()
    {
        return 'product_variant_attributes';
    }
}
