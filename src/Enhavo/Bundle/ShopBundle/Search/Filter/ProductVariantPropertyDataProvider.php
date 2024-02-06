<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataBuilder;
use Enhavo\Bundle\SearchBundle\Filter\Type\AbstractFilterType;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ProductVariantPropertyDataProvider extends AbstractFilterType
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        private ProductManager $productManager,
    )
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        if ($model instanceof ProductInterface) {
            $model = $model->getDefaultVariant();
        }

        if ($model === null) {
            return;
        }

        $proxy = $this->productManager->getVariantProxy($model);
        $value = $this->propertyAccessor->getValue($proxy, $options['property']);

        $builder->addData(new FilterData($key, $value));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['property']);
    }

    public static function getName(): string
    {
        return 'product_variant_property';
    }
}
