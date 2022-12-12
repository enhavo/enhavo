<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantCategoriesDataProvider extends AbstractType implements DataProviderInterface
{
    public function getData($resource, $options)
    {
        if ($resource instanceof ProductInterface) {
            $resource = $resource->getDefaultVariant();
        }

        if (!$resource instanceof ProductVariantInterface) {
            throw new \InvalidArgumentException(sprintf('The search filter type "product_variant_property" can only applied on ProductVariantInterface but "%s" given', get_class($resource)));
        }

        $return = [];
        $categories = $this->getCategories($resource);
        foreach ($categories as $category) {
            $data = new Data();
            $data->setValue($category->getId());
            $data->setKey('category');
            $return[] = $data;
        }

        return $return;
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

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }

    public function getType()
    {
        return 'product_variant_categories';
    }
}
