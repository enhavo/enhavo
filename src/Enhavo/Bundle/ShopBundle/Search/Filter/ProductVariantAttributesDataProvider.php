<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\Type\AbstractFilterType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;

class ProductVariantAttributesDataProvider extends AbstractFilterType
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        if ($model instanceof ProductInterface) {
            $model = $model->getDefaultVariant();
        }

        if (!$model instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_attributes" can only applied on ProductVariantInterface but "%s" given', get_class($model)));
        }

        foreach ($model->getProduct()->getAttributes() as $attribute) {
            $builder->addData(new FilterData('attribute-'.$attribute->getCode(), $attribute->getValue()));
        }
    }

    public static function getName(): string
    {
        return 'product_variant_attributes';
    }
}
