<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\Type\AbstractFilterType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

class ProductVariantCategoriesDataProvider extends AbstractFilterType
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        if ($model instanceof ProductInterface) {
            $model = $model->getDefaultVariant();
        }

        if (!$model instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_property" can only applied on ProductVariantInterface but "%s" given', get_class($model)));
        }

        $return = [];
        $categories = $this->getCategories($model);
        foreach ($categories as $category) {
            $builder->addData(new FilterData('category', $category->getId()));
        }
    }

    private function getCategories(ProductVariantInterface $resource): array
    {
        $categories = [];

        /** @var Term[] $productCategories */
        $productCategories = $resource->getProduct()->getCategories();
        foreach ($productCategories as $productCategory) {
            if (!in_array($productCategory, $categories)) {
                $categories[] = $productCategory;
            }
            $parents = $productCategory->getParents();
            foreach($parents as $parent) {
                if (!in_array($parent, $categories)) {
                    $categories[] = $parent;
                }
            }
        }

        return $categories;
    }

    public static function getName(): string
    {
        return 'product_variant_categories';
    }
}
