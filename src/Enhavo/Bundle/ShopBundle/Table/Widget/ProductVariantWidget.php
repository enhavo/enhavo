<?php

namespace Enhavo\Bundle\ShopBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class ProductVariantWidget extends AbstractTableWidget
{
    public function render($options, $resource)
    {
        if(!$resource instanceof ProductInterface) {
            return '';
        }

        $path = $this->container->get('router')->generate('enhavo_shop_product_variant_index', [
            'productId' => $resource->getId()
        ]);

        return $this->renderTemplate('EnhavoShopBundle:Admin:Table/product-variant-widget.html.twig', [
            'path' => $path
        ]);
    }

    public function getType()
    {
        return 'shop_product_variant';
    }
}