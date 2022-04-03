<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;

class ProductVariantProxyFactory
{
    public function __construct(
        private string $class
    ) {}

    public function createNew(ProductVariantInterface $productVariant)
    {
        return new $this->class($productVariant);
    }
}
