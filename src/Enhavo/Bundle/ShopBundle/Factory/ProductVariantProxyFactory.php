<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

class ProductVariantProxyFactory implements ProductVariantProxyFactoryInterface
{
    public function __construct(
        private string $class
    ) {}

    public function createNew(ProductVariantInterface $productVariant): ProductVariantProxyInterface
    {
        return new $this->class($productVariant);
    }
}
