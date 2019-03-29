<?php

namespace Enhavo\Bundle\ShopBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class ProductVariantWidget extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        if(!$resource instanceof ProductInterface) {
            return '';
        }

        $path = $this->container->get('router')->generate('enhavo_shop_product_variant_index', [
            'productId' => $resource->getId()
        ]);

        return $path;
    }

    public function getType()
    {
        return 'shop_product_variant';
    }
}
