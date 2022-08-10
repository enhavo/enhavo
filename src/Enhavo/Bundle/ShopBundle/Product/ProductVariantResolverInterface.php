<?php

namespace Enhavo\Bundle\ShopBundle\Product;

use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Symfony\Component\HttpFoundation\Request;

interface ProductVariantResolverInterface
{
    public function resolve(ProductInterface $productVariant, Request $request): ?ProductVariantInterface;
}
