<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantEnabledDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        if (!$resource instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_enabled" can only applied on ProductVariantInterface but "%s" given', get_class($resource)));
        }

        $data = new Data();
        $data->setValue($resource->isEnabled() && $resource->getProduct()->isEnabled());
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }

    public function getType()
    {
        return 'product_variant_enabled';
    }
}
