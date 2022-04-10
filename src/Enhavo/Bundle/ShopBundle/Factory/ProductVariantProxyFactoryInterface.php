<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

interface ProductVariantProxyFactoryInterface
{
    public function createNew(ProductVariantInterface $productVariant): ProductVariantProxyInterface;
}
