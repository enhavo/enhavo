<?php

namespace Enhavo\Bundle\ShopBundle\Product;

use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

interface ProductVariantProxyEnhancerInterface
{
    public function enhance(ProductVariantProxyInterface $productVariantProxy);
}
