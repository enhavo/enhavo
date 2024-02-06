<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\Type\AbstractFilterType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;

class ProductVariantEnabledDataProvider extends AbstractFilterType
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        if ($model instanceof ProductInterface) {
            $model = $model->getDefaultVariant();
        }

        if (!$model instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_enabled" can only applied on ProductVariantInterface but "%s" given', get_class($model)));
        }

        $builder->addData(new FilterData($key, $model->isEnabled() && $model->getProduct()->isEnabled()));
    }

    public static function getName(): string
    {
        return 'product_variant_enabled';
    }
}
