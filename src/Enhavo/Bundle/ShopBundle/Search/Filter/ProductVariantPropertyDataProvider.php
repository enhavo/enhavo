<?php

namespace Enhavo\Bundle\ShopBundle\Search\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Filter\Data;
use Enhavo\Bundle\SearchBundle\Filter\DataProviderInterface;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantPropertyDataProvider extends AbstractType implements DataProviderInterface
{
    public function __construct(
        private ProductManager $productManager,
    )
    {
    }

    public function getData($resource, $options)
    {
        if ($resource instanceof ProductInterface) {
            $resource = $resource->getDefaultVariant();
        }

        if ($resource === null) {
            return null;
        }

        $proxy = $this->productManager->getVariantProxy($resource);
        $value = $this->getProperty($proxy, $options['property']);
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
        return 'product_variant_property';
    }
}
